<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Monipê</title>

    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    <script src="{{ asset('js/app.js') }}"></script>

</head>

<body class="antialiased">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <label for="tipo_teste">Tipo de Teste</label>
                    <select class="form-control" id="tipo_teste" name="tipo_teste" onchange="showoptions(this)" required>
                        <option selected value="">Selecione o tipo de teste...</option>
                        <option value="atrasobidi">Atraso e Perda de pacotes</option>
                        <option value="atrasouni">Atraso unidirecional</option>
                        <option value="bandabbr">Banda (BBR)</option>
                        <option value="bandacubic">Banda (Cubic)</option>
                        <option value="traceroute">Traceroute</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="dados_extras">Dados do teste</label>
                    <div class="container" id="options-atrasobidi" style="display: none;">
                        <input type="checkbox" name="failures" id="failures"> failures<br>
                        <input type="checkbox" name="histogram-rtt" id="histogram-rtt"> histogram-rtt<br>
                        <input type="checkbox" name="histogram-ttl-reverse" id="histogram-ttl-reverse"> histogram-ttl-reverse<br>
                        <input type="checkbox" name="packet-count-lost-bidir" id="packet-count-lost-bidir"> packet-count-lost-bidir<br>
                        <input type="checkbox" name="packet-count-sent" id="packet-count-sent"> packet-count-sent<br>
                        <input type="checkbox" name="packet-duplicates-bidir" id="packet-duplicates-bidir"> packet-duplicates-bidir<br>
                        <input type="checkbox" name="packet-loss-rate-bidir" id="packet-loss-rate-bidir"> packet-loss-rate-bidir<br>
                        <input type="checkbox" name="packet-reorders-bidir" id="packet-reorders-bidir"> packet-reorders-bidir<br>
                    </div>
                    <div class="container" id="options2" style="display: none;">
                        <input type="checkbox" name="inicioImediato" id="inicioImediato" onclick="disableDate()"> Início imediato2<br>
                        <input type="checkbox" name="inicioImediato" id="inicioImediato" onclick="disableDate()"> Início imediato2<br>
                        <input type="checkbox" name="inicioImediato" id="inicioImediato" onclick="disableDate()"> Início imediato2<br>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="intervalo">Intervalo</label>
                    <select class="form-control" id="intervalo" name="intervalo">
                        <option value="3600">3600 (1H)</option>
                        <option value="43200">43200 (12H)</option>
                        <option value="86400">86400 (24H)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="origem">Origem</label>
                    <select class="form-control" id="origem" name="origem">
                        <option value="rj">PoP-RJ</option>
                        <option value="sp">PoP-SP</option>
                        <option value="mg">PoP-MG</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="destino">Destino</label>
                    <select class="form-control" id="destino" name="destino">
                        <option value="rj">PoP-RJ</option>
                        <option value="sp">PoP-SP</option>
                        <option value="mg">PoP-MG</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success" onclick="getAPIcmd()">Gerar</button>
                </div>
            </div>
        </div>
        <div class="card-body" id="api_info" style="display: none;">
            <div id="api_msg" style="font-size:large;"></div>
        </div>
    </div>

    <script>
        function showoptions(tipoTeste) {
            var type = tipoTeste.value;
            console.log(type);

            var options1 = document.getElementById('options-atrasobidi');
            var options2 = document.getElementById('options2');

            if (type == "atrasobidi") {
                options1.style.display = "block";
                options2.style.display = "none";
            } else if (type == "bandabbr") {
                options1.style.display = "none";
                options2.style.display = "block";
            } else {
                options1.style.display = "none";
                options2.style.display = "none";
            }
        }
        function getAPIcmd() {
            console.log('porra');
            var testeOption = document.getElementById('tipo_teste');
            var value = testeOption.options[testeOption.selectedIndex].value;
            console.log(value);

            var intervaloSelect = document.getElementById('intervalo');
            var intervalo = intervaloSelect.options[intervaloSelect.selectedIndex].value;

            console.log(intervalo);

            var origemSelect = document.getElementById('origem');
            var origem = origemSelect.options[origemSelect.selectedIndex].value;

            var destinoSelect = document.getElementById('destino');
            var destino = destinoSelect.options[destinoSelect.selectedIndex].value;

            console.log(origem);
            console.log(destino);

            var apiInfos = document.getElementById('api_info');
            var apiMsg = document.getElementById('api_msg');

            if (value == "atrasobidi") {

                var failures = document.getElementById('failures');
                var histogramRtt = document.getElementById('histogram-rtt');
                var histogramRttReverse = document.getElementById('histogram-ttl-reverse');
                var packetCountLostBidir = document.getElementById('packet-count-lost-bidir');
                var packetCountSent = document.getElementById('packet-count-sent');
                var packetDuplicatesBidir = document.getElementById('packet-duplicates-bidir');
                var packetLossRateBidir = document.getElementById('packet-loss-rate-bidir');
                var packetReordersBidir = document.getElementById('packet-reorders-bidir');

                apiMsg.innerHTML = "metadatakey=$(curl -s \"http://monipe-central.rnp.br/esmond/perfsonar/archive/?event-type=histogram-rtt&source=monipe-"+origem+"-atraso.rnp.br&destination=monipe-"+destino+"-atraso.rnp.br&time-range="+intervalo +"\" |  jq -r '.[] | .\"metadata-key\"')<br>";

                if (failures.checked == true) {
                    apiMsg.innerHTML += "curl -s \"http://monipe-central.rnp.br/esmond/perfsonar/archive/$metadatakey/failures/base?time-range="+intervalo+"\"<br>";
                }
                if (histogramRtt.checked == true) {
                    apiMsg.innerHTML += "curl -s \"http://monipe-central.rnp.br/esmond/perfsonar/archive/$metadatakey/histogram-rtt/base?time-range="+intervalo+"\"<br>";
                }
                if (histogramRttReverse.checked == true) {
                    apiMsg.innerHTML += "curl -s \"http://monipe-central.rnp.br/esmond/perfsonar/archive/$metadatakey/histogram-rtt-reverse/base?time-range="+intervalo+"\"<br>";
                }
                if (packetCountLostBidir.checked == true) {
                    apiMsg.innerHTML += "curl -s \"http://monipe-central.rnp.br/esmond/perfsonar/archive/$metadatakey/packet-count-lost-bidir/base?time-range="+intervalo+"\"<br>";
                }
                if (packetCountSent.checked == true) {
                    apiMsg.innerHTML += "curl -s \"http://monipe-central.rnp.br/esmond/perfsonar/archive/$metadatakey/packet-count-sent/base?time-range="+intervalo+"\"<br>";
                }
                if (packetDuplicatesBidir.checked == true) {
                    apiMsg.innerHTML += "curl -s \"http://monipe-central.rnp.br/esmond/perfsonar/archive/$metadatakey/packet-duplicates-bidir/base?time-range="+intervalo+"\"<br>";
                }
                if (packetLossRateBidir.checked == true) {
                    apiMsg.innerHTML += "curl -s \"http://monipe-central.rnp.br/esmond/perfsonar/archive/$metadatakey/packet-loss-rate-bidir/base?time-range="+intervalo+"\"<br>";
                }
                if (packetReordersBidir.checked == true) {
                    apiMsg.innerHTML += "curl -s \"http://monipe-central.rnp.br/esmond/perfsonar/archive/$metadatakey/packet-reorders-bidir/base?time-range="+intervalo+"\"<br>";
                }
                
                apiInfos.style.display = "block";

            } else {
                apiInfos.style.display = "none";
            }
        }
    </script>
</body>

</html>