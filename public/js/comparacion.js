let chartLine = null;
let chartBar = null;

$(document).ready(function () {
    $('#tabInforme').DataTable();

    $("#btnComparar").on("click", function () {
        getComparar();
    });

    $("#sucursal").on("change", function () {
        getPunto();
    });
});

/* ============================
   UTILIDAD
============================ */
function parseValor(valor) {
    if (typeof valor !== 'string') valor = String(valor);
    const limpio = valor.replace(',', '.').replace(/[^0-9.]/g, '');
    const num = parseFloat(limpio);
    return isNaN(num) ? 0 : num;
}

/* ============================
   FUNCIÓN PRINCIPAL
============================ */
function getComparar() {

    $.ajax({
        url: base_url + "/dashboard/getComparar",
        type: "POST",
        data: {
            id: $("#sucursal").val(),
            punto: $("#punto").val(),
            fechaIni: $("#fechaIni").val(),
            fechaFin: $("#fechaFin").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            mostrarLoader();
        },
        success: function (response) {
            console.log(response);

            if (!response.parametros || response.parametros.length === 0) {
                $("#chartLine, #chartBar")
                    .html('<p class="alert alert-warning">No hay datos</p>');
                return;
            }

            const parametros = response.parametros[0].map(p => p.Parametro);
            const folios = response.folios;

            /* ============================
               SERIES DE FOLIOS
            ============================ */
            let idx = 0;
            const series = response.parametros.map(item => {
                idx++;

                const valores = item.map(i => {
                    let valor;
                    if (i.Limite === 'N/A' || i.Limite.includes('-')) {
                        valor = parseValor(i.Resultado2);
                    } else {
                        valor = Math.max(parseValor(i.Resultado2), parseValor(i.Limite));
                    }
                    // Limitar a 3 decimales
                    return parseFloat(valor.toFixed(3));
                });

                const etiquetas = item.map(i => {
                    if (i.Limite === 'N/A' || i.Limite.includes('-')) {
                        const val = parseValor(i.Resultado2);
                        return parseFloat(val.toFixed(3)).toString();
                    }
                    const resultado = parseValor(i.Resultado2);
                    const limite = parseValor(i.Limite);
                    return resultado <= limite
                        ? `<${parseFloat(limite.toFixed(3))}`
                        : parseFloat(resultado.toFixed(3)).toString();
                });

                return {
                    name: folios[idx - 1],
                    data: valores,
                    label: {
                        show: false,
                        formatter: p => etiquetas[p.dataIndex]
                    }
                };
            });

            /* ============================
               SERIE DE LÍMITES (NUEVA)
            ============================ */
            const limitesN = response.limitesN || [];
            
            const valoresLimites = limitesN.map(limite => {
                if (limite === 'N/A' || (typeof limite === 'string' && limite.includes('-'))) {
                    return 0;
                }
                const valor = parseValor(limite);
                return parseFloat(valor.toFixed(3));
            });

            const etiquetasLimites = limitesN.map(limite => {
                if (limite === 'N/A' || (typeof limite === 'string' && limite.includes('-'))) {
                    return 'N/A';
                }
                const valor = parseValor(limite);
                return parseFloat(valor.toFixed(3)).toString();
            });

            // Agregar la serie de límites al final con color rojo
            series.push({
                name: 'Límites',
                data: valoresLimites,
                itemStyle: {
                    color: '#dc3545'  // Color rojo
                },
                label: {
                    show: false,
                    formatter: p => etiquetasLimites[p.dataIndex]
                }
            });

            /* ============================
               LIMPIAR INSTANCIAS
            ============================ */
            if (chartLine) chartLine.dispose();
            if (chartBar) chartBar.dispose();

            chartLine = echarts.init(document.getElementById('chartLine'));
            chartBar = echarts.init(document.getElementById('chartBar'));

            /* ============================
               STACKED LINE
            ============================ */
            const optionLine = {
                title: {
                    text: 'Resultados vs Límites',
                    subtext: $("#punto option:selected").text()
                },
                tooltip: { 
                    trigger: 'axis',
                    valueFormatter: value => parseFloat(value).toFixed(3)
                },
                legend: { top: 'top' },
                toolbox: {
                    left: 10,
                    feature: {
                        saveAsImage: {
                            title: 'Descargar gráfica',
                            pixelRatio: 2,
                            iconStyle: {
                                color: '#dc3545',
                                borderWidth: 0.5
                            }
                        }
                    }
                },
                xAxis: {
                    type: 'category',
                    data: parametros,
                    axisLabel: { rotate: 30 }
                },
                yAxis: { 
                    type: 'value',
                    axisLabel: {
                        formatter: value => parseFloat(value).toFixed(3)
                    }
                },
                dataZoom: [
                    { type: 'slider', xAxisIndex: 0, bottom: 0, height: 25 },
                    { type: 'inside', xAxisIndex: 0 }
                ],
                series: series.map((s, index) => {
                    const baseSeries = {
                        ...s,
                        type: 'line',
                        stack: 'total',
                        smooth: false,
                        areaStyle: { opacity: 0.25 }
                    };
                    
                    // Si es la última serie (Límites), agregar línea punteada
                    if (index === series.length - 1) {
                        return {
                            ...baseSeries,
                            lineStyle: {
                                type: 'dashed',  // Línea punteada
                                width: 2,
                                color: '#dc3545'  // Color rojo
                            },
                            areaStyle: { 
                                opacity: 0.1,
                                color: '#dc3545'
                            }
                        };
                    }
                    
                    return baseSeries;
                })
            };

            /* ============================
               BARRAS
            ============================ */
            const optionBar = {
                title: {
                    text: 'Comparación por Parámetro',
                    subtext: $("#punto option:selected").text()
                },
                tooltip: { 
                    trigger: 'axis', 
                    axisPointer: { type: 'shadow' },
                    valueFormatter: value => parseFloat(value).toFixed(3)
                },
                legend: { top: 'top' },
                toolbox: {
                    left: 10,
                    feature: {
                        saveAsImage: {
                            title: 'Descargar gráfica',
                            pixelRatio: 2,
                            iconStyle: {
                                color: '#dc3545',
                                borderWidth: 0.5
                            }
                        }
                    }
                },
                xAxis: {
                    type: 'category',
                    data: parametros,
                    axisLabel: { rotate: 45 }
                },
                yAxis: { 
                    type: 'value',
                    axisLabel: {
                        formatter: value => parseFloat(value).toFixed(3)
                    }
                },
                dataZoom: [
                    { type: 'slider', xAxisIndex: 0, bottom: 0, height: 25 },
                    { type: 'inside', xAxisIndex: 0 }
                ],
                series: series.map(s => ({
                    ...s,
                    type: 'bar'
                }))
            };

            chartLine.setOption(optionLine, true);
            chartBar.setOption(optionBar, true);

            window.addEventListener('resize', () => {
                chartLine.resize();
                chartBar.resize();
            });
            getResumenIa(response);
        },
        complete: function () {
            ocultarLoader();
        }
    });
}
function mostrarLoader() {
    document.getElementById('loaderComparar').classList.add('active');
}

function ocultarLoader() {
    document.getElementById('loaderComparar').classList.remove('active');
}
function iniciarAnalisisIA() {
    const iaComentario = document.getElementById('iaComentario');

    iaComentario.innerHTML = `
        <span class="spinner-border spinner-border-sm text-success me-2"></span>
        Analizando resultados...
    `;
}


/* ============================
   CARGAR PUNTOS
============================ */
function getPunto() {
    $.ajax({
        url: base_url + "/dashboard/getPunto",
        type: "POST",
        data: {
            id: $("#sucursal").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {

            let rowsHtml = '<option selected>Seleciona una opción</option>';
            rowsHtml += `<optgroup label="Puntos generales">`;

            response.punto2.forEach(item => {
                rowsHtml += `<option value="${item.Id_punto}">${item.Punto_muestreo}</option>`;
            });

            rowsHtml += `</optgroup><optgroup label="Puntos siralab">`;

            response.punto1.forEach(item => {
                rowsHtml += `<option value="${item.Id_punto}">${item.Punto}</option>`;
            });

            rowsHtml += `</optgroup>`;

            $("#punto").html(rowsHtml);
        }
    });
}

async function getResumenIa(data) {

    const iaBox = document.getElementById('iaComentario');
    iaBox.innerHTML = 'Analizando resultados...';

    const datosParametro = JSON.stringify(data.parametros2);
    const datosLimites = JSON.stringify(data.limitesN);
    const datosSolicitud = JSON.stringify(data.solicitud);
    const datosFolio = JSON.stringify(data.folios);

    const promptInicial = `
    REGLAS OBLIGATORIAS:
    - No describas el JSON.
    - No menciones datos técnicos ni formatos.
    - No hagas introducción ni cierre.
    - Usa lenguaje claro para público general.
    - No inventes valores.
    - No generar tablas
    - No menciones la palabra JSON ni lista de datos.
    - No mostrar el responsable del análisis.
    - No responder en ingles
    - No visualizar datos de solicitud o folios.
    - Solo parametros exedentes

    TAREA:
    Analiza los resultados de la solicitud comparando los parámetros entre sí y con la norma indicada y que parametros estan incrementando su valor.
    y no visualizar datos de la solicitud o folios. y No mostrar el responsable del análisis.

    INSTRUCCIONES:
    1. Describe si los resultados son estables o muestran aumento.
    2. Menciona únicamente los parámetros que NO cumplen.
    3. Explica el posible riesgo de forma sencilla.
    5. Solo un resumen breve y conciso de mis parametros.
    6. La respuseta tiene que ser en español.
    7. Cada posicion esta relacionada porque son datos para una grafica a exepcion de parametros una posicion tiene varios parametros que equivale a una posicion del folio y la solicitud,
    8. No visualizar datos de la solicitud o folios.
    9. No mostrar el responsable del análisis.

    DATOS:
    Norma: ${data.solicitud[0].Clave_norma}
    parametros:${datosParametro}
    Limites de norma:${datosLimites}
    Folios:${datosFolio}
    RESPUESTA: Los parámetros que no cumplen son y van aumentando su valor

    De acuerdo a los datos proporcionados en formato JSON pero no deben ser mencionados, analisa la tendencia por los parametros analisados por cada fecha y da una conclusion con respecto al historico y 
    muestra todos los parametros que tienen tendencia elevada.
    Omite el parametro de Materia flotante.
    No analizar la tendencia de los parametros con resultados negativos y N/A.
    No responder en ingles.

    Dame una conclusion respecto a los parametros que resultaron con tendencia elevada tambien dame recomendaciones para poder controlar dichos parametros.
     Una regla muy importante que tienes que tomar siempre en cuenta es que todo lo que se te pregunte apartir de ahora solo seran respuestas completamente en ESPAÑOL.
    `;

    try {
        const response = await getCompletion(promptInicial);

        iaBox.innerHTML = response.respuesta
            .replace(/\n/g, '<br>')
            .replace(/- /g, '• ');

    } catch (e) {
        iaBox.innerHTML = 'No fue posible generar el análisis automático.';
    }
}

async function getCompletion(prompt) {
    iniciarAnalisisIA()
    const response = await fetch(
        'https://pushed-compaq-sep-lands.trycloudflare.com/iaollama/public/api/getRequest',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                prompt: prompt
            })
        }
    );

    return await response.json();
}
chatContainer.classList.add('chat-wrapper');

function addMessage(text, type = 'ia') {
    const msg = document.createElement('div');
    msg.className = `chat-msg ${type === 'ia' ? 'chat-ia' : 'chat-user'}`;
    msg.innerHTML = text.replace(/\n/g, '<br>');
    chatContainer.appendChild(msg);
}
