function submitForm() { //overrides default behavior of reloading page
  var accountInfo = getInput();
  if(validateInput(accountInfo)){
    makeRequest();
  } else {

  }
}

function getInput(){
  var input = {};
  //empty fields, passwords match
  input.firstName = document.getElementById("fName").value;
  input.lastName = document.getElementById("lName").value;
  input.userName = document.getElementById("uName").value;
  input.passWord1 = document.getElementById("pWord1").value;
  input.passWord2 = document.getElementById("pWord2").value;
  if(document.getElementById("accessPatient").checked){
    input.accessLevel = 0; //
  } else {
    input.accessLevel = 1; //medical office assistant(admin)
  } 
  return input;
}

function validateInput(i){
  debugger;
  var validFirstName = false;
  if(/\S/.test(i.firstName)) { //contains a char at the very least, so not empty or whitespace 
    validFirstName = true;
  } else{
    var errSpan = document.createElement('span');
    errSpan.setAttribute('style', 'display:block; color:red;'); 
    var errText = document.createTextNode('ERROR: You must enter a first name.');
    document.getElementById("errorMessages").appendChild(errSpan);
    errSpan.appendChild(errText);
    validFirstName = false;
  }
  
  var validLastName = false;
  if(/\S/.test(i.lastName)){ //contains a char at the very least, so not empty or whitespace 
    validLastName = true;
  } else{
    var errSpan = document.createElement('span');
    errSpan.setAttribute('style', 'display:block; color:red;'); 
    var errText = document.createTextNode('ERROR: You must enter a last name.');
    document.getElementById("errorMessages").appendChild(errSpan);
    errSpan.appendChild(errText);
    validLastName = false;
  }
  
  var validUserName = false;
  if(/^\S*$/.test(i.userName)){ //no white spaces
    validUserName = true;
  } else{
    var errText = document.createTextNode('ERROR: Your user name cannot contain spaces.');
    document.getElementById("errorMessages").appendChild(errText);
    validUserName = false;
  }

  var validPassWord1 = false;
  if(/^\S*$/.test(i.passWord1)){ //no white spaces
    validPassWord1  = true;
  } else{
    var errText = document.createTextNode('ERROR: Your user name cannot contain spaces.');
    document.getElementById("errorMessages").appendChild(errText);
    validPassWord1  = false;
  }

  var validPassWord2 = false;  
  if(/^\S*$/.test(i.passWord2)){ //no white spaces
    validPassWord2 = true;
  } else{
    var errText = document.createTextNode('ERROR: Your user name cannot contain spaces.');
    document.getElementById("errorMessages").appendChild(errText);
    validPassWord2 = false;
  }

  var passWordMatch = false;
  if(i.passWord1 == i.passWord2) { //contains a char at the very least, so not empty or whitespace 
    passWordMatch = true;
  } else{
    var errSpan = document.createElement('span');
    errSpan.setAttribute('style', 'display:block; color:red;'); 
    var errText = document.createTextNode('ERROR: Passwords must match.');
    document.getElementById("errorMessages").appendChild(errSpan);
    errSpan.appendChild(errText);
    validFirstName = false;
    passWordMatch = false;
  }

  return (validFirstName && validLastName && validUserName && validPassWord1 && validPassWord2 && passWordMatch)? true:false;
}


function makeRequest(){


}