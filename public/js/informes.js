$(document).ready(function () {
    let tablaInforme = null;


    $("#btnBuscar").on("click", function () {
        getPreInforme()
    });
    // getGrafica1()
});
/**
 * Función robusta para convertir valores de la tabla (ej: "10,5" o "<2") a un número.
 * @param {string} valor - El valor original del campo Resultado2 o Limite.
 * @returns {number} El valor numérico limpio, o 0 si no es un número.
 */
function parseValor(valor) {
    if (typeof valor !== 'string') {
        valor = String(valor);
    }
    // 1. Reemplaza comas por puntos (para decimales)
    // 2. Remueve el signo '<' u otros caracteres no numéricos al inicio
    const valorLimpio = valor.replace(',', '.').replace(/[^0-9.]/g, '');

    const num = parseFloat(valorLimpio);
    return isNaN(num) ? 0 : num;
}
function prepararDatos(model, limitesN, limitesC) {

    const parametros = model.map(i => i.Parametro);

    const resultados = model.map(i => parseValor(i.Resultado2));

    const limites = limitesN.map(i => {
        if (i === 'N/A' || i.includes('-')) return 0;
        return parseValor(i);
    });

    const etiquetas = model.map((i, idx) => {
        if (i.Limite === 'N/A' || i.Limite.includes('-')) {
            return i.Resultado2;
        }

        const r = parseValor(i.Resultado2);
        const l = parseValor(i.Limite);
        return r < l ? `<${i.Limite}` : i.Resultado2;
    });

    return { parametros, resultados, limites, etiquetas };
}


var chartDom = document.getElementById('line-container');

function getGraficaBarras(model, limitesN, limitesC) {

    const { parametros, resultados, limites } =
        prepararDatos(model, limitesN, limitesC);

    const chartDom = document.getElementById('bar-container');
    const myChart = echarts.init(chartDom);

    const option = {
        title: {
            text: 'Comparación por parámetro',
            subtext: 'Resultado vs Límite'
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            bottom: 0
        },

        // ===== BOTÓN DESCARGA NATIVO =====
        toolbox: {
            show: true,
            left: '10px',
            top: '10px',
            itemSize: 18,      // tamaño del icono (más chico = más fino)
            itemGap: 12,

            feature: {
                saveAsImage: {
                    show: true,
                    title: 'Descargar gráfica',
                    type: 'png',
                    pixelRatio: 2,
                    backgroundColor: '#ffffff',

                    iconStyle: {
                        color: '#dc3545',       // rojo
                        borderColor: '#dc3545',
                        borderWidth: 0.5          // 🔴 CLAVE: hace el icono más delgado
                    },

                    emphasis: {
                        iconStyle: {
                            color: '#a71d2a',
                            borderColor: '#a71d2a',
                            borderWidth: 0.5
                        }
                    }
                }
            }
        },
        // ================================

        xAxis: {
            type: 'category',
            data: parametros,
            axisLabel: {
                rotate: 30,
                interval: 0
            }
        },
        yAxis: {
            type: 'value',
            name: 'Valor'
        },
        series: [
            {
                name: 'Resultado',
                type: 'bar',
                data: resultados,
                barWidth: '40%'
            },
            {
                name: 'Límite',
                type: 'bar',
                data: limites,
                barWidth: '40%',
                itemStyle: {
                    color: 'red'
                }
            }
        ]
    };

    myChart.setOption(option);

    window.addEventListener('resize', () => myChart.resize());
}



function getGrafica1(model, limitesN, limitesC) {

    const { parametros, resultados, limites, etiquetas } = prepararDatos(model, limitesN, limitesC);

    const chartDom = document.getElementById('line-container');
    const myChart = echarts.init(chartDom);

    const option = {
        title: {
            text: 'Resultados vs Límites',
            subtext: 'Gráfica Stacked Line'
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            top: 'top'
        },

        // ===== TOOLBOX DESCARGA =====
        toolbox: {
            show: true,
            left: '10px',
            top: '10px',
            itemSize: 18,
            itemGap: 12,
            feature: {
                saveAsImage: {
                    title: 'Descargar gráfica',
                    type: 'png',
                    pixelRatio: 2,
                    backgroundColor: '#ffffff',
                    iconStyle: {
                        color: '#dc3545',
                        borderColor: '#dc3545',
                        borderWidth: 0.5
                    },
                    emphasis: {
                        iconStyle: {
                            color: '#a71d2a',
                            borderColor: '#a71d2a',
                            borderWidth: 0.5
                        }
                    }
                }
            }
        },
        // ============================

        xAxis: {
            type: 'category',
            data: parametros,
            axisLabel: {
                rotate: 30
            }
        },
        yAxis: {
            type: 'value',
            name: 'Valor'
        },

        series: [
            {
                name: 'Resultado Medido',
                type: 'line',
                stack: 'total',            // 🔑 CLAVE STACK
                smooth: false,
                areaStyle: { opacity: 0.35 }, // 🔑 Hace visible el apilado
                data: resultados,
                label: {
                    show: false,
                    formatter: p => etiquetas[p.dataIndex]
                }
            },
            {
                name: 'Límite Normativo',
                type: 'line',
                stack: 'total',            // 🔑 MISMO STACK
                smooth: false,
                areaStyle: { opacity: 0.2 },
                lineStyle: {
                    type: 'dashed',
                    color: 'red'
                },
                data: limites
            }
        ]
    };

    myChart.setOption(option);

    window.addEventListener('resize', () => myChart.resize());
}



function descargarGrafica() {
    const chartDom = document.getElementById('main');
    const myChart = echarts.getInstanceByDom(chartDom);

    const img = myChart.getDataURL({
        type: 'png',
        pixelRatio: 2,
        backgroundColor: '#fff'
    });

    const link = document.createElement('a');
    link.href = img;
    link.download = 'resultados_vs_limites.png';
    link.click();
}

async function getCompletion(prompt) {
    const response = await fetch(
        'https://pray-swim-wales-pace.trycloudflare.com/iaollama/public/api/getRequest',
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

async function probar() {
    const respuesta = await getCompletion("cuanto es 2+2");
    console.log(respuesta);
}
async function sendToAI(message) {
    const res = await fetch("http://51.51.51.2:86/api/ask", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify({
            message: message
        })
    });

    const data = await res.json();
    return data;
}
const chatContainer = document.getElementById('chatContainer');
chatContainer.classList.add('chat-wrapper');

function addMessage(text, type = 'ia') {
    const msg = document.createElement('div');
    msg.className = `chat-msg ${type === 'ia' ? 'chat-ia' : 'chat-user'}`;
    msg.innerHTML = text.replace(/\n/g, '<br>');
    chatContainer.appendChild(msg);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}
async function enviarPreguntaUsuario(pregunta) {

    addMessage(pregunta, 'user');
    addMessage('Pensando...', 'ia');

    const contexto = `
    Eres un asistente que YA analizó resultados de laboratorio.
    Responde SOLO con base en el análisis previo y la norma.
    Una regla muy importante que tienes que tomar siempre en cuenta es que todo lo que se te pregunte apartir de ahora solo seran respuestas completamente en ESPAÑOL.
    Pregunta del usuario:
    "${pregunta}"
    `;

    try {
        const response = await getCompletion(contexto);

        // elimina "Pensando..."
        chatContainer.lastChild.remove();

        addMessage(response.respuesta, 'ia');
    } catch (e) {
        addMessage('No pude responder en este momento.', 'ia');
    }
}
document.getElementById('sendChat').addEventListener('click', () => {
    const input = document.getElementById('chatInput');
    if (!input.value.trim()) return;

    enviarPreguntaUsuario(input.value);
    input.value = '';
});

document.getElementById('chatInput').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        document.getElementById('sendChat').click();
    }
});


async function getResumenIa(data) {
    // const datosResultado = JSON.stringify(data.limitesC);
    const datosResultado = JSON.stringify(data.model2);
    const datosNorma = JSON.stringify(data.limitesN);

  
    const promptInicial = `
            Rol y contexto
        Actúa como un especialista en cumplimiento normativo ambiental y sanitario en México, con experiencia en análisis fisicoquímicos y microbiológicos de agua y en la aplicación de Normas Oficiales Mexicanas (NOM) y normas técnicas vigentes.

        Objetivo
        Analizar un informe de resultados de laboratorio, identificar los valores reportados, compararlos contra los límites máximos permisibles de la Norma Oficial Mexicana aplicable, y proponer acciones técnicas de mejora para los parámetros que se encuentren fuera de norma.

        Instrucciones

        Identifica y lista todos los parámetros analizados en el informe.

        Para cada parámetro:

        Valor reportado

        Unidad de medida

        Límite máximo permisible según la NOM aplicable

        Norma y numeral de referencia (por ejemplo: NOM-001-SEMARNAT-2021, NOM-127-SSA1-2021, etc.).

        Determina claramente si el resultado:

        Cumple

        No cumple

        Presenta los resultados en una tabla clara y estructurada.

        Para cada parámetro fuera de límite, proporciona:


        Sugerencias de tratamiento para los parametros que estan fuera de los limites permisibles.

        Buenas prácticas operativas para prevenir reincidencia.

        Utiliza lenguaje técnico, claro y profesional, adecuado para un informe comercial o regulatorio.

        No inventes valores normativos; si falta información, indícalo explícitamente.

        Datos de entrada
        [Pegar aquí el informe de resultados del laboratorio, tabla o texto completo]

        Formato de salida esperado

        Resumen ejecutivo de cumplimiento

        Tabla comparativa Resultados vs Límite NOM
        Análisis de incumplimientos

        Recomendaciones técnicas de mejora.
        los valores que aparescan como negativos , no se deben de tomar en la interpretacion del analisis , estos valores estan dentro del cumplimiento de la norma.
        No se debe mostrar ninguna Tabla Comparativa ni Tabla Comparativa Resultados vs Límite.
        los limites proporcionados de la norma son los maximos permisibles, 
        en caso de que el limite no tenga un valor numerorico o tenga un valor N/A no debe compararse y se debe de interpretar como que el parametro cumple con la norma.
        No presentar los resultados detallados por  cada uno de los parámetro no importa si cumple o incumple.
        Solo tomar en cuento los limires proporcionados en datosNorma para la interpretacion.
        Nunca pero nunca muestres Análisis Detallado de los Parámetros.

        Punto de muestreo: ${data.punto.Punto}
                NORMA: ${data.solicitud.Norma}
            RESULTADOS:
            ${datosResultado}
            LIMITES:
            ${datosNorma}
    `




    chatContainer.innerHTML = '';
    addMessage('Analizando resultados...', 'ia');

    try {
        const response = await getCompletion(promptInicial);
        chatContainer.innerHTML = '';
        addMessage(response.respuesta, 'ia');
    } catch (e) {
        addMessage('Error al analizar los datos.', 'ia');
    }
}

// Función para el efecto de escritura (ajustada para ser más rápida)
async function escribirTexto(texto, elementoHTML) {
    const caracteres = texto.split('');
    let i = 0;

    while (i < caracteres.length) {
        if (caracteres[i] === '<' && caracteres[i + 1] === 'b' && caracteres[i + 2] === 'r' && caracteres[i + 3] === '>') {
            // Si detecta la secuencia de la etiqueta <br>
            elementoHTML.innerHTML += '<br>';
            i += 4; // Salta los 4 caracteres de la etiqueta
        } else {
            // Si es un caracter normal, lo agrega
            elementoHTML.innerHTML += caracteres[i];
            i++;
        }
        await new Promise(resolve => setTimeout(resolve, 5));
    }
}
function getPreInforme() {

    $.ajax({
        url: base_url + "/dashboard/getPreInforme",
        type: "POST",
        data: {
            folio: $("#folio").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if ($.fn.DataTable.isDataTable('#tabInforme')) {
                tablaInforme.clear().destroy();
            }

            console.log(response)
            getResumenIa(response)
                ,
            getGrafica1(response.model, response.limitesN, response.limitesC);
            getGraficaBarras(response.model, response.limitesN, response.limitesC);

            // Generar las filas dinámicamente
            let rowsHtml = "";
            let cont = 0
            let cumple = 0;
            response.model.forEach(function (item) {
                const limiteN = response.limitesN[cont];
                let estadoCumplimiento = '';
                let valorAmostrar = item.Resultado2; // Variable para el TD de Resultado2

                // Intentamos obtener el valor numérico, importante si hay texto.
                // const resultadoNum = response.limitesC[cont];
                const resultadoNum = parseFloat(item.Resultado2);

                // --- LÓGICA DE CUMPLIMIENTO (La misma que ya tienes) ---
                // 1. Manejar el caso N/A
                if (limiteN === 'N/A' || limiteN === 'N.A.') {
                    estadoCumplimiento = 'N/A';
                    cumple++
                }
                // 2. Manejar el caso de RANGO (ej: "6-9")
                else if (limiteN.includes('-')) {
                    const partesRango = limiteN.split('-');
                    const limiteInferior = parseFloat(partesRango[0]);
                    const limiteSuperior = parseFloat(partesRango[1]);

                    if (resultadoNum >= limiteInferior && resultadoNum <= limiteSuperior) {
                        estadoCumplimiento = 'Cumple';
                        cumple++
                    } else {
                        estadoCumplimiento = 'No Cumple';
                        cumple--
                    }
                }
                // 3. Manejar el caso de LÍMITE ÚNICO (donde se aplica la nueva regla)
                else {
                    const limiteNum = parseFloat(limiteN);

                    // Lógica de Cumplimiento
                    if (resultadoNum < limiteNum) {
                        estadoCumplimiento = 'Cumple';
                        cumple++
                    } else {
                        estadoCumplimiento = 'No Cumple';
                        cumple--
                    }

                    // 💡 **NUEVA LÓGICA PARA EL CAMPO Resultado2 (valorAmostrar)**
                    // Si el resultado es MAYOR O IGUAL que el límite (No cumple/Límite excedido)

                }

                // --- Generación de la Fila HTML ---
                rowsHtml += `
        <tr onclick="">
            <td>${saltoCada3Palabras(item.Parametro)}</td>
            <td>${response.limitesC[cont]}</td> <td>${limiteN}</td>
            <td>${estadoCumplimiento}</td>
        </tr>
    `;
                cont++;
            });
            // Insertar las filas generadas en el tbody
            let datosExtra = 'Norma reporte: ' + response.solicitud.Clave_norma + ' | Punto: ' + response.punto.Punto
            $("#datosFolio").html(datosExtra);
            $("#informe").html(rowsHtml);
            // $('#tabInforme').DataTable();
            tablaInforme = $('#tabInforme').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    zeroRecords: "No se encontraron resultados",
                    paginate: {
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                }
            });

            let porcentajeCumple = (cumple / response.model.length) * 100;

            $('#txtCumple').text(porcentajeCumple.toFixed(1) + "%");

            if (porcentajeCumple >= 100) {
                $('#txtCritico').html(`<span class="fw-bold text-success">Aceptable</span>`);
            } else if (porcentajeCumple >= 99) {
                $('#txtCritico').html(`<span class="fw-bold text-warning">En riesgo</span>`);
            } else {
                $('#txtCritico').html(`<span class="fw-bold text-danger">Crítico</span>`);
            }


        },
        error: function (xhr, status, error) {
            console.error("Error en la petición:", error);
        },
    });
    // getPreInformeExtra()
}
function saltoCada3Palabras(texto) {
    const palabras = texto.split(' ');
    let resultado = '';

    palabras.forEach((p, i) => {
        resultado += p + ' ';
        if ((i + 1) % 3 === 0) resultado += '<br>';
    });

    return resultado.trim();
}

function getPreInformeExtra() {
    $.ajax({
        url: base_url + "/dashboard/getPreInformeExtra",
        type: "POST",
        data: {
            folio: $("#folio").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            console.log(response)


        },
        error: function (xhr, status, error) {
            console.error("Error en la petición:", error);
        },
    });
}