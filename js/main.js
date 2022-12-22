

function sign_in_form(event) {
  event.preventDefault();

  let email = $("#email").val().trim();
  let password = $("#password").val().trim();

  if (password.length < 3) return;

  let fdata = { ch: "sign_in", email, password };

  let sbutton = $("#sbutton").html(); //grab the initial content

  $("#errmsg").html("");

  $("#sbutton").html(
    '<span class="fa fa-spin fa-spinner fa-2x"></span>Please wait...'
  );

  $.ajax({
    type: "POST",
    url: "./connect/main.php",
    data: fdata,
    success: function (data) {
      console.log(data);
      if (data === "PASS") {
        $("#sbutton").html(
          '<span style="font-size:16px; color:#092; font-weight: bold" class="text-success"> Signing in ......</span>'
        );
        $("form").trigger("reset");
        window.location.replace("./user");
      } else if (data === "PASSO") {
        $("#sbutton").html(
          '<span style="font-size:16px; color:#092; font-weight: bold" class="text-success"> Signing in ......</span>'
        );
        $("form").trigger("reset");
        window.location.replace("./search.php");
      } else {
        $("#sbutton").html(sbutton);
        $("#errmsg").html('<span class="text-danger">' + data + "</span>");
      }
    },
  });
}

function sign_up_form(event) {
  event.preventDefault();

  let email = $("#email").val().trim();
  let password = $("#password").val().trim();
  let password2 = $("#password2").val().trim();
  let fname = $("#fname").val().trim();
  let lname = $("#lname").val().trim();

  if (password.length < 3) return;

  let fdata = { ch: "sign_up", email, fname, lname, password, password2 };

  let sbutton = $("#sbutton").html(); //grab the initial content

  $("#errmsg").html("");

  $("#sbutton").html(
    '<span class="fa fa-spin fa-spinner fa-2x"></span>Please wait...'
  );

  $.ajax({
    type: "POST",
    url: "./connect/main.php",
    data: fdata,
    success: function (data) {
      console.log(data);
      if (data === "PASS") {
        $("#sign_form").html(
          '<div class="alert alert-success py-5 text-center"> <span class="fa text-success fa-4x fa-check-circle"></span> <br><br> Sign up successful. You can now login</div>'
        );
        $("form").trigger("reset");
      } else {
        $("#sbutton").html(sbutton);
        $("#errmsg").html('<span class="text-danger">' + data + "</span>");
      }
    },
  });
}




 // timeout before a callback is called

 let timeout;

 // traversing the DOM and getting the input and span using their IDs

 let password = document.getElementById('password')
 let strengthBadge = document.getElementById('password_strength')

 // The strong and weak password Regex pattern checker
let strongPassword = new RegExp(
  "(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})"
);
let mediumPassword = new RegExp(
  "((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{6,}))|((?=.*[a-z])(?=.*[A-Z])(?=.*[^A-Za-z0-9])(?=.{8,}))"
);

function StrengthChecker(PasswordParameter) {
  // We then change the badge's color and text based on the password strength

  if (strongPassword.test(PasswordParameter)) {
    strengthBadge.style.backgroundColor = "green";
    strengthBadge.textContent = "Strong";
  } else if (mediumPassword.test(PasswordParameter)) {
    strengthBadge.style.backgroundColor = "blue";
    strengthBadge.textContent = "Medium";
  } else {
    strengthBadge.style.backgroundColor = "red";
    strengthBadge.textContent = "Weak";
  }
}


password.addEventListener("input", () => {

  //The badge is hidden by default, so we show it

  strengthBadge.style.display= 'block'
  clearTimeout(timeout);

  //We then call the StrengChecker function as a callback then pass the typed password to it

  timeout = setTimeout(() => StrengthChecker(password.value), 500);

  //Incase a user clears the text, the badge is hidden again

  if(password.value.length !== 0){
      strengthBadge.style.display != 'block'
  } else{
      strengthBadge.style.display = 'none'
  }
});

