var appointmentID;
var action;

function approveReject(button){
  if(button.value=='approve'){
    appointmentID=button.id;
    action=1;
  }
  else if(button.value=='reject'){
    appointmentID=button.id;
    action=2;
  }
  createRequest(action,appointmentID);
  return false;
}

function createRequest(act,aID){
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
  httpRequest.open('POST','http://web.engr.oregonstate.edu/~watsokel/cs290/finalProject/updateAppt.php',true);
  httpRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  var postParameters = 'action='+act+'&apptID='+aID;
  httpRequest.send(postParameters);
}


function processResponse(){
  try{
    console.log(httpRequest.readyState);
    if(httpRequest.readyState===4 && httpRequest.status===200){
      var response = JSON.parse(httpRequest.responseText);
      debugger;
      updatePage(response);
    }else console.log('Problem with the request');
  }
  catch(e){
    console.log('Caught Exception: ' + e);
  }
}

function updatePage(r){
  if(r.success) {
    window.location='http://web.engr.oregonstate.edu/~watsokel/cs290/finalProject/administratorMain.php';
  }
}