<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet"/>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js "></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- <script src=" {{asset('js/cep/dadosCep.js') }} "></script> --}}
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Busca Cep</title>
</head>
<body>
  <section class="pb-4">
    <div class="border rounded-5">
      <form  id="form-pesquisa">
      <section class="w-100 p-4 pb-4 d-flex justify-content-center align-items-center flex-column">
        <div>
          <div class="input-group">
            <div class="form-outline" data-mdb-input-init="" data-mdb-input-initialized="true">
              <input type="search" id="cepDaBusca" class="form-control">
              <label class="form-label" for="cepDaBusca" style="margin-left: 0px;">Cep</label>
            <div class="form-notch"><div class="form-notch-leading" style="width: 9px;"></div><div class="form-notch-middle" style="width: 47.2px;"></div><div class="form-notch-trailing"></div></div></div>
            <button type="submit" class="btn btn-primary" data-mdb-ripple-init="">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </section>
    </form>
      
    </div>

    
    <div class="row" id="resultados">
     
    </div>
  </section>
</body>

<script>
  $(document).ready(function(){
    $('#cepDaBusca').mask('00000-000');

    $( "#form-pesquisa" ).on( "submit", function( event ) {
  
      event.preventDefault();

      if ($("#cepDaBusca").val() == "") {
        
          Swal.fire({
            text: "Cep é obrigatorio",
            icon: "info"
          });

          return;
        }

        var cep = $("#cepDaBusca").val();
        res = cep.replace('-', "");

        $.ajax({
          url: "/cep/"+cep,
          type: "GET",
          data: "",
          async: true,
          contentType: false,
          processData: false,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (res) {
            console.log(res);

            let timerInterval;
            Swal.fire({
              title: "Pesquisando!",
              html: "",
              timer: 1000,
              timerProgressBar: true,
              didOpen: () => {
                Swal.showLoading();
                const timer = Swal.getPopup().querySelector("b");
                timerInterval = setInterval(() => {
                  timer.textContent = `${Swal.getTimerLeft()}`;
                }, 100);
              },
              willClose: () => {
                clearInterval(timerInterval);
              }
            }).then((result) => {
              /* Read more about handling dismissals below */
              if (result.dismiss === Swal.DismissReason.timer) {

                if(res.erro){
                  Swal.fire({
                    text: "Cep não encontrado",
                    icon: "error"
                  });

                  return;
                }
                var resultado =  '<div class="col-sm-3 "> '+
                              '<div class="card w-75" style="width: 18rem;"> '+
                                '<ul class="list-group list-group-light list-group-small"> '+
                                '<li class="list-group-item px-3">'+res.cep+'</li>'+
                                  '<li class="list-group-item px-3">'+res.logradouro+'</li>'+
                                  '<li class="list-group-item px-3">'+res.bairro+'</li>'+
                                  '<li class="list-group-item px-3">'+res.localidade+'-'+res.uf+'</li>'+
                                  // '<li class="list-group-item px-3"><a href="https://www.google.com/maps/place/'+res.logradouro +'-'+ res.bairro+'-'+res.localidade+'" target="_blank" rel="noopener noreferrer">fgfgfgfgf</a></li>'+
                                '</ul>'+
                              '</div>';
                            '</div>';

                 $("#resultados").prepend(resultado);
              }
            });


            
          },
          error: function (x, t, m) {
            
          }
        });

    });

	});


 
</script>
</html>