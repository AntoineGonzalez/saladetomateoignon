$(document).ready(async function() {
  //  let shopbag = await getShopbag().catch(console.error)

    let stripe = Stripe('pk_test_ZN5qgSfUMOguZvvHdXsEOEeZ00Y1uusbKJ');
    let elements = stripe.elements();

    let card = elements.create("card", {
        style: {
            base: {
                hidePostalCode: true,
                fontSize: '24px',
                fontSmoothing: 'antialiased',
                ':-webkit-autofill': {
                    color: 'black',
                },
                '::placeholder': {
                    color: 'black',
                },
            },
        }
    });
    let cardElements = $("#card-element")
    if(cardElements.length !== 0) {
        card.mount("#card-element");
    }

    let form = document.getElementById('payment-form');
    let clientSecret = $('#submit').attr("data-secret");
    let cardError = $("#card-errors");
    let cardSuccess = $("#card-success");
    let submitBtn = $("#pay-now");
    let userMail = $("#pay-now").attr("data-customer-mail");
    let userId = $("#pay-now").attr("data-customer-id");

    if(form) {
      form.addEventListener('submit', function(ev) {
          $("#submit").hide()
          $("#loader").show()
          $("#pay-now").hide();
          $("#card-element").hide();
          ev.preventDefault();
          stripe.confirmCardPayment(clientSecret, {
              payment_method: {
                  card: card,
                  billing_details: {
                      name: userMail
                  }
              }
          }).then(function(result) {
              if (result.error) {
                  cardError.text(result.error.message);
                  cardError.show()
                  $("#submit").show()
                  $("#loader").hide()
                  $("#pay-now").show();
                  $("#card-element").show();
              } else {
                  // The payment has been processed!
                  if (result.paymentIntent.status === 'succeeded') {
                      cardSuccess.html(`Paiement effectué avec succès ! Vous allez être redirigés`)
                      cardSuccess.show();
                      submitBtn.hide();
                      cardError.hide();

                      postPurshase(1).then(function(res) {
                        setTimeout(function(){
                          window.location.href = 'purshases';
                        }, 1000);
                      }).catch(console.error)
                  }
              }
          });
      });
    }

    function postPurshase(paid) {
        let hour = $("#delivery").val()
        let today = new Date()
        let year = today.getFullYear()
        let month = today.getMonth() + 1
        let day = today.getDate()

        if(month < 10) {
            month = '0'+month
        }

        if(day < 10) {
            day = '0'+day
        }

        return new Promise(function(resolve, reject) {
            $.ajax({
                url : `http://saladetomateoignons.ddns.net/process_purchase`,
                type : 'POST',
                data: {
                    paid: paid,
                    deliveryHour: `${year}-${month}-${day} ${hour}:00`
                },
                success: function(res){
                    resolve(res)
                },
                error: function(err){
                    reject(err)
                }
            });
        })
    }

    $(".retractable").each(function () {
        $(this).on("click" , function(){
            if($(this).next().is(":visible")){
                $(this).next().hide();
            }else{
                $(this).next().show();
            }
        });
    });

    $("#pay-now").on("click", function() {
        let hour = $("#delivery").val()
        if(hour) {
            let valid = hour.match(/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/)
            if(valid && valid.length !== 0) {
                if($("#payment-form").is(":visible")) {
                    $(this).text("Payer en carte bancaire")
                    $(this).css("background-color", "#007bff")
                    $("#payment-form").hide()
                    $("#card-element").hide()
                    $("#pay-after").show()
                } else {
                    $(this).text("Annuler le paiement")
                    $(this).css("background-color", "red")
                    $("#payment-form").show()
                    $("#card-element").show()
                    $("#pay-after").hide()
                }
            } else {
                toastr.error('Précise une heure de préparation pour le chef', 'Erreur');
            }
        } else {
            toastr.error('Précise une heure de préparation pour le chef', 'Erreur');
        }
    })

    $("#pay-after").on("click", function() {
        let hour = $("#delivery").val()
        if(hour) {
            let valid = hour.match(/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/)
            if(valid.length !== 0) {
                $("#pay-now").hide()
                $("#loader").show()
                postPurshase(0).then(function(res) {

                    setTimeout(function(){
                        window.location.href = 'purshases';
                    }, 1000);
                }).catch(console.error)
            } else {
                toastr.error('Précise une heure de préparation pour le chef', 'Erreur');
            }
        } else {
            toastr.error('Précise une heure de préparation pour le chef', 'Erreur');
        }
    })
});
