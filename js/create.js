var httpRequest;
var allMessagesDiv;

function submitForm() {
  clearErrorMessages();
  var progSpan = document.createElement('span');
  progSpan.className = 'alert alert-info';
  progSpan.setAttribute('style', 'display:block; color:blue;'); 
  var progGlyph = document.createElement('span');
  progGlyph.className = 'glyphicon glyphicon-time';
  var progText = document.createTextNode(' Creating Account...');
  progSpan.appendChild(progGlyph);
  progSpan.appendChild(progText);
  allMessagesDiv.appendChild(progSpan);
  var accountInfo = getInput();
  if(validateInput(accountInfo)){
    createRequest(accountInfo);
  }
  return false;
}

function getInput(){
  var input = {};
  input.firstName = document.getElementById("fName").value;
  input.lastName = document.getElementById("lName").value;
  input.userName = document.getElementById("uName").value;
  input.passWord1 = document.getElementById("pWord1").value;
  input.passWord2 = document.getElementById("pWord2").value;
  if(document.getElementById("accessPatient").checked){
    input.accessLevel = 0; //
  } else if(document.getElementById("accessMOA").checked) {
    input.accessLevel = 1; //medical office assistant(admin)
  } 
  return input;
}

function removeElement(node){
  node.parentNode.removeChild(node);
}

function clearErrorMessages(){
  if(document.getElementById('allMessages')){
    removeElement(document.getElementById('allMessages'));
  }
  allMessagesDiv = document.createElement('div');
  allMessagesDiv.id = 'allMessages';
  document.getElementById('status').appendChild(allMessagesDiv); 
}

function errorCreatingAcct(errString){
  var errSpan = document.createElement('span');
  errSpan.className = 'alert alert-danger';
  errSpan.setAttribute('style', 'display:block; color:red;'); 
  var errDiv = document.createElement('div');
  errDiv.id = 'errDiv';
  errDiv.setAttribute('style', 'display:block;');
  var errGlyph = document.createElement('span');
  errGlyph.className = 'glyphicon glyphicon-exclamation-sign';
  var errText = document.createTextNode(errString);  
  errDiv.appendChild(errGlyph);
  errDiv.appendChild(errText);
  errSpan.appendChild(errDiv);
  allMessagesDiv.appendChild(errSpan);
}

function validateInput(i){
  clearErrorMessages();
  var validFirstName = validLastName = validUserName = validPassWord1 = validPassWord2 = passWordMatch = false;
  if(/\S/.test(i.firstName)) { //contains at least one char, so not empty or whitespace 
    validFirstName = true;
  } else{
    errorCreatingAcct(' ERROR: You must enter a first name.');
  }
  
  if(/\S/.test(i.lastName)){ 
    validLastName = true;
  } else{
    errorCreatingAcct(' ERROR: You must enter a last name.');
  }
  
  if(/^\S*$/.test(i.userName)){ //cannot be empty, and no white spaces allowed at all
    validUserName = true;
  } else{
    errorCreatingAcct(' ERROR: Your user name cannot contain spaces.');
  }

  if(/^\S*$/.test(i.passWord1)){ 
    validPassWord1  = true;
  } else{
    errorCreatingAcct(' ERROR: Password cannot contain spaces.');
  }

  if(/^\S*$/.test(i.passWord2)){ 
    validPassWord2 = true;
  } else{
    errorCreatingAcct(' ERROR: Re-entered password cannot contain spaces.');
  }

  if(i.passWord1 == i.passWord2) { //contains a char at the very least, so not empty or whitespace 
    passWordMatch = true;
  } else{
    errorCreatingAcct(' ERROR: Passwords must match.');
  }
  return (validFirstName && validLastName && validUserName && validPassWord1 && validPassWord2 && passWordMatch)? true:false;
}

function createRequest(acct){
  if(window.XMLHttpRequest) httpRequest = new XMLHttpRequest();
  else if(window.ActiveXObject){
    try { 
      httpRequest = new ActiveXObject('Msxml2.XMLHTTP');
    }
    catch(e){
      try{  
        httpRequest = new ActiveXObject('Microsoft.XMLHTTP');
      } catch(e){}
    }
  }
  if (!httpRequest){
    alert('Unable to create XMLHTTP instance.');
    return false;
  }
  httpRequest.onreadystatechange = processResponse;
  httpRequest.open('POST','http://web.engr.oregonstate.edu/~watsokel/cs290/finalProject/createAcct.php',true);
  httpRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  var postParameters = 'firstName='+acct.firstName+'&lastName='+acct.lastName+'&userName='+acct.userName+'&passWord='+acct.passWord1+'&accessLevel='+acct.accessLevel;
  httpRequest.send(postParameters);
}

function processResponse(){
  try{
    console.log(httpRequest.readyState);
    if(httpRequest.readyState===4 && httpRequest.status===200){
      var response = JSON.parse(httpRequest.responseText);
      accountCreation(response);
    }else console.log('Problem with the request');
  }
  catch(e){
    console.log('Caught Exception: ' + e);
  }
}

function accountCreation(r){
  clearErrorMessages();
  if(r.success){
    removeElement(document.getElementById("formContainer"));
    var successSpan = document.createElement('span');
    successSpan.className = 'alert alert-success';
    successSpan.setAttribute('style', 'display:block; color:green;'); 
    var successGlyph = document.createElement('span');
    successGlyph.className = 'glyphicon glyphicon-ok-circle';
    var successText = document.createTextNode(' Account successfully created!');
    var successAnchor = document.createElement('a');
    var successAnchorText = document.createTextNode(' Click here to login.');
    successAnchor.appendChild(successAnchorText);
    successAnchor.href='index.php';
    successSpan.appendChild(successGlyph);
    successSpan.appendChild(successText);
    successSpan.appendChild(successAnchor);
    allMessagesDiv.appendChild(successSpan);
  } else {
    var failSpan = document.createElement('span');
    failSpan.className = 'alert alert-danger';
    failSpan.setAttribute('style', 'display:block; color:red;'); 
    var failGlyph = document.createElement('span');
    failGlyph.className = 'glyphicon glyphicon-remove-circle';
    var failText = document.createTextNode(' Unable to create account. That user name already exists. Please choose a new user name.');
    failSpan.appendChild(failGlyph);
    failSpan.appendChild(failText);
    allMessagesDiv.appendChild(failSpan);
  }
}