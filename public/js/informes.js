


$(document).ready(function () {
    $('#tabInforme').DataTable();

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


function getGrafica1(model,limiteN,limiteC) {
    var chartDom = document.getElementById('main');

    // --- 1. LÓGICA DE ALTURA DINÁMICA (Para que se vean todos los parámetros) ---
    const paramCount = model.length;
    const baseHeight = 100; // Espacio inicial para títulos/leyendas
    const rowHeight = 40; // Altura mínima asignada por cada parámetro
    
    // Calcula la altura mínima, asegurando un mínimo de 400px
    const dynamicHeight = Math.max(baseHeight + (paramCount * rowHeight), 400); 
    
    // Establece la altura del DIV antes de inicializar ECharts
    chartDom.style.height = dynamicHeight + 'px';
    
    var myChart = echarts.init(chartDom);
    var option;

    const nombreParametro = model.map(item => item.Parametro);
    
    // --- 2. PREPARACIÓN DE DATOS ---

    // Array de valores numéricos que determina la LONGITUD de la barra.
    const resultadoNumerico = model.map(item => {
        const limiteStr = item.Limite;
        const resultadoNum = parseValor(item.Resultado2); 
        
        if (limiteStr === 'N/A' || limiteStr.includes('-')) {
            return resultadoNum;
        }
        
        const limiteNum = parseValor(limiteStr);
        
        // La barra se dibuja con el valor MÁXIMO entre el resultado medido y el límite.
        return Math.max(resultadoNum, limiteNum);
    });
    
    // Array de texto que se muestra como ETIQUETA sobre la barra (ej: "<2").
    const resultadoEtiqueta = model.map(item => {
        const limiteStr = item.Limite;
        const resultadoNum = parseValor(item.Resultado2); 
        const limiteNum = parseValor(limiteStr);

        if (limiteStr === 'N/A' || limiteStr.includes('-')) {
            return item.Resultado2;
        }
        
        // Si el resultado es menor al límite, mostramos la notación "<Límite"
        if (resultadoNum < limiteNum) {
            return `<${limiteStr}`;
        }
        
        // Si es mayor o igual, mostramos el valor medido real
        return item.Resultado2;
    });

    // Extraemos los límites para la barra de referencia
    const limites = model.map(item => {
        if (item.Limite === "N/A" || item.Limite.includes('-')) {
            return 0; // No graficamos N/A ni Rangos en la barra de límite.
        }
        return parseValor(item.Limite);
    });

    // --- 3. CONFIGURACIÓN DE LA SERIE ---
    var seriesDinamicas = [
        {
            name: 'Resultado Medido',
            type: 'bar',
            data: resultadoNumerico, // Usa el valor numérico (Lógica MAX)
            label: {
                show: true,
                position: 'right', // Muestra la etiqueta sobre la barra
                formatter: function(params) {
                    // Muestra el texto especial (<Límite)
                    return limiteC[params.dataIndex]; 
                }
            }
        },
        {
            name: 'Límite Normativo',
            type: 'bar',
            data: limites 
        }
    ];

    // --- 4. OPCIONES DEL GRÁFICO ---
    option = {
        title: { 
            text: 'Resultados vs. Límites', 
            subtext: 'Comparativa de Parámetros' 
        },
        tooltip: { 
            trigger: 'axis', 
            axisPointer: { type: 'shadow' } 
        },
        legend: {
            top: 'top', // Posición en la parte superior del contenedor
            left: 'center' // Centrar horizontalmente
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true // Asegura que las etiquetas largas del Eje Y quepan
        },
        xAxis: { 
            type: 'value', 
            boundaryGap: [0, 0.01], 
            name: 'Valor' 
        },
        yAxis: { 
            type: 'category', 
            data: nombreParametro, 
            name: 'Parámetros',
            axisLabel: {
                interval: 0, // Fuerza a mostrar todas las etiquetas de parámetro
            }
        }, 
        series: seriesDinamicas 
    };

    option && myChart.setOption(option);
    
    // Agrega el listener de redimensionamiento para el auto-ajuste horizontal
    window.addEventListener('resize', function() {
        myChart.resize();
    });
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
  

   const response = await fetch('http://sistemasofia.ddns.net:86/iaollama/public/api/getRequest', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute('content')
        },
        body: JSON.stringify({
            prompt: prompt
        })
    });

    const data = await response.json();

    return data;
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
async function getResumenIa(data) {
    const datosJSON = JSON.stringify(data.model);
    const prompt = `
                De acuerdo a los resultados mostrados compáralos contra los limites máximos permisibles de la norma de referencia, 
                dime que parámetros están excedidos y cual es el tratamiento adecuado para que mis valores estén dentro de estos 
                limites de acuerdo al punto de muestreo que es el giro de mi empresa, en caso de que no haya una norma de referencia 
                que marque los limites máximos permisibles dime que mejoras puedo hacer para que mis resultados tengan valores mas bajos
                 o se mantengan de acuerdo al punto de muestreo o la naturaleza de la muestra, acontinuacion de proporciono los datos: 
                 norma: ${data.solicitud.Clave_norma} , punto de muestreo: ${data.punto.Punto}, resultados obtenidos en formato Json
                 `
const promptres = `
                De acuerdo a los resultados mostrados compáralos contra los limites máximos permisibles de la norma de referencia, 
                dime que parámetros están excedidos y cual es el tratamiento adecuado para que mis valores estén dentro de estos 
                limites de acuerdo al punto de muestreo que es el giro de mi empresa, en caso de que no haya una norma de referencia 
                que marque los limites máximos permisibles dime que mejoras puedo hacer para que mis resultados tengan valores mas bajos
                 o se mantengan de acuerdo al punto de muestreo o la naturaleza de la muestra, acontinuacion de proporciono los datos: 
                 norma: ${data.solicitud.Clave_norma} , punto de muestreo: ${data.punto.Punto}, resultados obtenidos en formato Json ${datosJSON}
                 `
    console.log(prompt)
    const output = document.querySelector("#resumenia");

    if (!data) {
        console.warn("No hay datos para analizar.");
        output.innerHTML = "Por favor, proporciona datos para el análisis.";
        return;
    }

    // Limpiamos el contenido anterior antes de la llamada
    output.innerHTML = '';

    // Paso 1: Crea y muestra el mensaje de carga
    const loadingMessage = document.createElement('div');
    loadingMessage.textContent = 'Analizando datos...';
    loadingMessage.style.textAlign = 'center';
    loadingMessage.style.fontStyle = 'italic';
    output.appendChild(loadingMessage);

    try {
        // Paso 2: Realiza la llamada a la API y espera la respuesta
        const response = await getCompletion(prompt);

        // Limpiamos el div antes de mostrar el texto de la IA
        output.innerHTML = '';

         let textoRespuesta = response.respuesta;

            // Reemplazamos los saltos de línea con <br>
            textoRespuesta = textoRespuesta.replace(/\n/g, '<br>');

            // Llamamos a la función que escribe el texto
            await escribirTexto(textoRespuesta, output);
    } catch (error) {
        console.error("Error al obtener la respuesta de la IA:", error);
        output.innerHTML = "Hubo un error en la comunicación con el servicio de IA.";
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
            console.log(response)
            // getResumenIa(response)
,
            getGrafica1(response.model, response.limitesN,response.limitesC)
            // Generar las filas dinámicamente
            let rowsHtml = "";
            let cont = 0
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
    } 
    // 2. Manejar el caso de RANGO (ej: "6-9")
    else if (limiteN.includes('-')) {
        const partesRango = limiteN.split('-');
        const limiteInferior = parseFloat(partesRango[0]);
        const limiteSuperior = parseFloat(partesRango[1]);
        
        if (resultadoNum >= limiteInferior && resultadoNum <= limiteSuperior) {
            estadoCumplimiento = 'Cumple';
        } else {
            estadoCumplimiento = 'No Cumple';
        }
    } 
    // 3. Manejar el caso de LÍMITE ÚNICO (donde se aplica la nueva regla)
    else {
        const limiteNum = parseFloat(limiteN);
        
        // Lógica de Cumplimiento
        if (resultadoNum < limiteNum) {
            estadoCumplimiento = 'Cumple';
        } else {
            estadoCumplimiento = 'No Cumple';
        }

        // 💡 **NUEVA LÓGICA PARA EL CAMPO Resultado2 (valorAmostrar)**
        // Si el resultado es MAYOR O IGUAL que el límite (No cumple/Límite excedido)
       
    }
    
    // --- Generación de la Fila HTML ---
    rowsHtml += `
        <tr onclick="">
            <td>${item.Parametro}</td>
            <td>${response.limitesC[cont]}</td> <td>${limiteN}</td>
            <td>${estadoCumplimiento}</td>
        </tr>
    `;
    cont++;
});
            // Insertar las filas generadas en el tbody
            let datosExtra = 'Norma reporte: ' + response.solicitud.Clave_norma + ' | Punto: '+response.punto.Punto
            $("#datosFolio").html(datosExtra);
            $("#informe").html(rowsHtml);
            $('#tabInforme').DataTable();
        },
        error: function (xhr, status, error) {
            console.error("Error en la petición:", error);
        },
    });
}