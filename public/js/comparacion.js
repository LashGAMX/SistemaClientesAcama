$(document).ready(function () {
    $('#tabInforme').DataTable();

    $("#btnComparar").on("click", function () {
        getComparar();
    });

    $("#sucursal").on("change", function () {
        getPunto();
    });

    $("#tipoGrafica").on("change", function () {
        getComparar();
    });
});

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

/* ============================
   UTILIDAD PARA PARSEAR VALORES
============================ */
function parseValor(valor) {
    if (typeof valor !== 'string') valor = String(valor);
    const limpio = valor.replace(',', '.').replace(/[^0-9.]/g, '');
    const num = parseFloat(limpio);
    return isNaN(num) ? 0 : num;
}

/* ============================
   CONSTRUCTOR DE OPCIONES
============================ */
function construirOption(tipo, nombreParametro, folios, series) {

    const base = {
        title: { text: 'Comparación | ' + $("#punto option:selected").text() },
        tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
        legend: { data: folios, top: 'top' },

        // ✅ BOTÓN DESCARGAR IMAGEN
        toolbox: {
            show: true,
            right: 20,
            feature: {
                saveAsImage: {
                    type: 'png',              // png | jpeg
                    name: 'comparacion_grafica',
                    title: 'Descargar imagen',
                    pixelRatio: 2             // alta calidad
                }
            }
        }
    };
    switch (tipo) {

        case 'bar-vertical':
            return {
                ...base,
                xAxis: { type: 'category', data: nombreParametro, axisLabel: { rotate: 45 } },
                yAxis: { type: 'value' },
                series: series.map(s => ({ ...s, type: 'bar' }))
            };

        case 'line':
            return {
                ...base,
                xAxis: { type: 'category', data: nombreParametro },
                yAxis: { type: 'value' },
                series: series.map(s => ({ ...s, type: 'line', smooth: true }))
            };

        case 'area':
            return {
                ...base,
                xAxis: { type: 'category', data: nombreParametro },
                yAxis: { type: 'value' },
                series: series.map(s => ({ ...s, type: 'line', areaStyle: {} }))
            };

        case 'stack':
            return {
                ...base,
                xAxis: { type: 'category', data: nombreParametro },
                yAxis: { type: 'value' },
                series: series.map(s => ({ ...s, type: 'bar', stack: 'total' }))
            };

        case 'radar':
            return {
                title: { text: 'Comparación Radar' },
                tooltip: {},
                legend: { data: folios },
                radar: {
                    indicator: nombreParametro.map(p => ({
                        name: p,
                        max: 100
                    }))
                },
                series: [{
                    type: 'radar',
                    data: series.map(s => ({
                        name: s.name,
                        value: s.data
                    }))
                }]
            };

        default: // bar-horizontal
            return {
                ...base,
                xAxis: { type: 'value' },
                yAxis: { type: 'category', data: nombreParametro },
                series: series.map(s => ({ ...s, type: 'bar' }))
            };
    }
}

/* ============================
   FUNCIÓN PRINCIPAL
============================ */
let myChart = null;

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
        success: function (response) {

            if (!response.parametros || response.parametros.length === 0) {
                $("#main").html('<p class="alert alert-warning">No se encontraron datos.</p>');
                return;
            }

            const nombreParametro = response.parametros[0].map(i => i.Parametro);
            const folios = response.folios;

            let idx = 0;
            const seriesDinamicas = response.parametros.map(item => {
                idx++;

                const datosNumericos = item.map(i => {
                    if (i.Limite === 'N/A' || i.Limite.includes('-')) {
                        return parseValor(i.Resultado2);
                    }
                    return Math.max(parseValor(i.Resultado2), parseValor(i.Limite));
                });

                const etiquetas = item.map(i => {
                    if (i.Limite === 'N/A' || i.Limite.includes('-')) return i.Resultado2;
                    return parseValor(i.Resultado2) <= parseValor(i.Limite)
                        ? `<${i.Limite}`
                        : i.Resultado2;
                });

                return {
                    name: folios[idx - 1],
                    data: datosNumericos,
                    label: {
                        show: true,
                        formatter: p => etiquetas[p.dataIndex]
                    }
                };
            });

            const chartDom = document.getElementById('main');
            const tipoGrafica = $("#tipoGrafica").val();
            const esHorizontal = (tipoGrafica === 'bar-horizontal');

            chartDom.style.height = tipoGrafica === 'radar'
                ? '500px'
                : Math.max(400, nombreParametro.length * 90) + 'px';

            if (myChart) {
                myChart.dispose();
            }

            myChart = echarts.init(chartDom);

            const option = construirOption(
                tipoGrafica,
                nombreParametro,
                folios,
                seriesDinamicas
            );

            /* ============================
               SCROLL (dataZoom)
            ============================ */
            option.dataZoom = esHorizontal
                ? [
                    { type: 'slider', yAxisIndex: 0, right: 0, start: 0, end: 40 },
                    { type: 'inside', yAxisIndex: 0 }
                ]
                : [
                    { type: 'slider', xAxisIndex: 0, bottom: 0, start: 0, end: 30 },
                    { type: 'inside', xAxisIndex: 0 }
                ];

            option.grid = {
                left: '4%',
                right: esHorizontal ? '10%' : '4%',
                bottom: '15%',
                containLabel: true
            };

            myChart.setOption(option, true);

            window.addEventListener('resize', () => myChart.resize());
        }
    });
}
