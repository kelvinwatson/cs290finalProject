var httpRequest;
var loginMessagesDiv;

function submitForm() {
  clearErrorMessages();
  var progSpan = document.createElement('span');
  progSpan.className = 'alert alert-info';
  progSpan.setAttribute('style', 'display:block; color:blue;'); 
  var progGlyph = document.createElement('span');
  progGlyph.className = 'glyphicon glyphicon-time';
  var progText = document.createTextNode(' Logging in...');
  progSpan.appendChild(progGlyph);
  progSpan.appendChild(progText);
  loginMessagesDiv.appendChild(progSpan);
  
  var loginInfo = getInput();
  if(validateInput(loginInfo)){
    createRequest(loginInfo);
  }
  return false;
}

function getInput(){
  var input = {};
  input.userName = document.getElementById("loginUser").value;
  input.passWord = document.getElementById("loginPW").value;
  return input;
}

function removeElement(node){
  node.parentNode.removeChild(node);
}

function clearErrorMessages(){
  if(document.getElementById('loginMessages')){
    removeElement(document.getElementById('loginMessages'));
  }
  loginMessagesDiv = document.createElement('div');
  loginMessagesDiv.id = 'loginMessages';
  document.getElementById('loginStatus').appendChild(loginMessagesDiv); 
}

function errorLoggingIn(errString){
  var errSpan = document.createElement('span');
  errSpan.className = 'alert alert-danger';
  errSpan.setAttribute('style', 'display:block; color:red;'); 
  var errDiv = document.createElement('div');
  errDiv.setAttribute('style', 'display:block;');
  var errGlyph = document.createElement('span');
  errGlyph.className = 'glyphicon glyphicon-exclamation-sign';
  var errText = document.createTextNode(errString);  
  errDiv.appendChild(errGlyph);
  errDiv.appendChild(errText);
  errSpan.appendChild(errDiv);
  loginMessagesDiv.appendChild(errSpan);
}



function validateInput(i){
  clearErrorMessages();
  var validUserName = validPassWord = false;
  if(/^\S*$/.test(i.userName)){ //no white spaces allowed
    validUserName = true;
  } else{
    errorLoggingIn(' ERROR: User name cannot contain spaces.');
  }
  if(/^\S*$/.test(i.passWord)){ 
    validPassWord  = true;
  } else{
    errorLoggingIn(' ERROR: Password cannot contain spaces.');
  }
  return (validUserName && validPassWord)? true:false;
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
  httpRequest.open('POST','http://web.engr.oregonstate.edu/~watsokel/cs290/finalProject/login.php',true);
  httpRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  var postParameters = 'userName='+acct.userName+'&passWord='+acct.passWord;
  httpRequest.send(postParameters);
}

function processResponse(){
  try{
    console.log(httpRequest.readyState);
    if(httpRequest.readyState===4 && httpRequest.status===200){
      var response = JSON.parse(httpRequest.responseText);
      loginVerdict(response);
    }else console.log('Problem with the request');
  }
  catch(e){
    console.log('Caught Exception: ' + e);
  }
}

function loginVerdict(r){
  clearErrorMessages();
  loginMessagesDiv = document.createElement('div');
  loginMessagesDiv.id = 'loginMessages';
  document.getElementById('loginStatus').appendChild(loginMessagesDiv);
  if(r.loggedIn){
    if(r.accessLevel==0)
      window.location='http://web.engr.oregonstate.edu/~watsokel/cs290/finalProject/patientMain.php';
    else if(r.accessLevel==1){
      window.location='http://web.engr.oregonstate.edu/~watsokel/cs290/finalProject/administratorMain.php';
    }
  } else {
    errorLoggingIn(' Invalid login information. Please try again.');
  } 
}
