<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                margin: 0;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .row {
                display: -ms-flexbox; /* IE10 */
                display: flex;
                -ms-flex-wrap: wrap; /* IE10 */
                flex-wrap: wrap;
                margin: 0 -16px;
              }

              .col-25 {
                -ms-flex: 25%; /* IE10 */
                flex: 25%;
              }

              .col-50 {
                -ms-flex: 50%; /* IE10 */
                flex: 50%;
              }

              .col-75 {
                -ms-flex: 75%; /* IE10 */
                flex: 75%;
              }

              .col-25,
              .col-50,
              .col-75 {
                padding: 0 16px;
              }

              .container {
                background-color: #f2f2f2;
                padding: 5px 20px 15px 20px;
                border: 1px solid lightgrey;
                border-radius: 3px;
              }

              input[type=text] {
                width: 100%;
                margin-bottom: 20px;
                padding: 12px;
                border: 1px solid #ccc;
                border-radius: 3px;
              }

              label {
                margin-bottom: 10px;
                display: block;
              }

              .icon-container {
                margin-bottom: 20px;
                padding: 7px 0;
                font-size: 24px;
              }

              .btn {
                background-color: #04AA6D;
                color: white;
                padding: 12px;
                margin: 10px 0;
                border: none;
                width: 100%;
                border-radius: 3px;
                cursor: pointer;
                font-size: 17px;
              }

              .btn:hover {
                background-color: #45a049;
              }

              span.price {
                float: right;
                color: grey;
              }

              /* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other (and change the direction - make the "cart" column go on top) */
              @media (max-width: 800px) {
                .row {
                  flex-direction: column-reverse;
                }
                .col-25 {
                  margin-bottom: 20px;
                }
              }
        </style>
    </head>
    <body class="container content">

    <h2 class="title">Rave Shop</h2>

    <div class="card" style="width: 18rem; margin: 20px auto 10px;">
        <img class="card-img-top" src="https://s3.amazonaws.com/images.ecwid.com/images/13798140/844353180.jpg" alt="Flutterwave Home Jersey">
        <form id="paymentForm">
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email-address" required />
            </div>
            <div class="form-group">
              <label for="amount">Amount</label>
              <input type="text" id="amount" required />
            </div>
            <div class="form-group">
              <label for="first-name">First Name</label>
              <input type="text" id="first-name" />
            </div>
            <div class="form-group">
              <label for="last-name">Last Name</label>
              <input type="text" id="last-name" />
            </div>

            <div class="form-submit">
                <button type="submit" onclick="payWithPaystack()"> Pay with Paystack </button>
            </div>

            <div class="form-submit">
                <script src="https://checkout.flutterwave.com/v3.js"></script>
              <button type="submit" onclick="makePayment();"> Pay with Flutterwave </button>
            </div>



          </form>
          <script src="https://js.paystack.co/v1/inline.js"></script>
          <form name="paymentForm" enctype="multipart/form-data" id="paymentForm" action="{{ route('placeOrder') }}" method="POST">{{ csrf_field() }}
            <div class="col-50">
                <h3>Payment</h3>
                <label for="fname">Accepted Cards</label>
                <div class="icon-container">
                  <i class="fa fa-cc-visa" style="color:navy;"></i>
                  <i class="fa fa-cc-amex" style="color:blue;"></i>
                  <i class="fa fa-cc-mastercard" style="color:red;"></i>
                  <i class="fa fa-cc-discover" style="color:orange;"></i>
                </div>
                <label for="ccnum">Credit card number</label>
                <input type="text" id="ccnum" name="card_number" placeholder="1111-2222-3333-4444">
                <label for="expmonth">Exp Month</label>
                <input type="text" id="expmonth" name="expiry_month" placeholder="September">

                <div class="row">
                  <div class="col-50">
                    <label for="expyear">Exp Year</label>
                    <input type="text" id="expyear" name="expiry_year" placeholder="2018">
                  </div>
                  <div class="col-50">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="352">
                  </div>
                </div>
              </div>
            <div class="form-submit">
                <button type="submit" onclick="payWithBema();"> Pay with BemaPay </button>
            </div>
          </form>

        </div>
    </div>
    <script>
        const paymentForm = document.getElementById('paymentForm');
        const API_publicKey = "FLWPUBK_TEST-3f806ff7a060cba7ab79698f3f6716e3-X";
        paymentForm.addEventListener("submit", makePayment, false);
        function makePayment(e) {
            e.preventDefault();
            let handler = FlutterwaveCheckout({
            public_key: API_publicKey,
            tx_ref: ''+Math.floor((Math.random() * 1000000000) + 1),
            amount: 5000,
            currency: "NGN",
            payment_options: " ",
            redirect_url: // specified redirect URL
              "https://callbacks.piedpiper.com/flutterwave.aspx?ismobile=34",
            meta: {
              consumer_id: 23,
              consumer_mac: "92a3-912ba-1192a",
            },
            customer: {
              email: document.getElementById("email-address").value,
              phone_number: "08102909304",
              name: "Flutterwave Developers",
            },
            callback: function (data) {
              console.log(data);
            },
            onclose: function() {
              // close modal
            },
            customizations: {
              title: "My store",
              description: "Payment for items in cart",
              logo: "https://assets.piedpiper.com/logo.png",
            },
          });
          handler.openIframe();
        }
      </script>

      <script>
        const paymentForm = document.getElementById('paymentForm');
        const API_publicKey = "bspk_test_549fadfad9";
        paymentForm.addEventListener("submit", payWithBema, false);
        function payWithBema(e) {
            e.preventDefault();
            let handler = FlutterwaveCheckout({
            public_key: API_publicKey,
            tx_ref: ''+Math.floor((Math.random() * 1000000000) + 1),
            amount: 5000,
            currency: "NGN",
            payment_options: " ",
            redirect_url: // specified redirect URL
              "https://callbacks.piedpiper.com/flutterwave.aspx?ismobile=34",
            meta: {
              consumer_id: 23,
              consumer_mac: "92a3-912ba-1192a",
            },
            customer: {
              email: document.getElementById("email-address").value,
              phone_number: "08102909304",
              name: "Flutterwave Developers",
            },
            callback: function (data) {
              console.log(data);
            },
            onclose: function() {
              // close modal
            },
            customizations: {
              title: "My store",
              description: "Payment for items in cart",
              logo: "https://assets.piedpiper.com/logo.png",
            },
          });
          handler.openIframe();
        }
      </script>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script>
          const paymentForm = document.getElementById('paymentForm');
          paymentForm.addEventListener("submit", payWithPaystack, false);
          function payWithPaystack(e) {
            e.preventDefault();
            let handler = PaystackPop.setup({
              key: 'pk_test_e7aa24268609f683fbaf840b089fdeccd905d70c', // Replace with your public key
              email: document.getElementById("email-address").value,
              amount: document.getElementById("amount").value * 100,
              ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
              // label: "Optional string that replaces customer email"
              onClose: function(){
                alert('Window closed.');
              },
              callback: function(response){
                let message = 'Payment complete! Reference: ' + response.reference;
                alert(message);
              }
            });
            handler.openIframe();
          }
        </script>

    </body>
</html>
