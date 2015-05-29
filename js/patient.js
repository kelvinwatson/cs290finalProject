var httpRequest;
var allMessagesDiv;

function submitForm() {
  debugger;
  clearErrorMessages();
  var progSpan = document.createElement('span');
  progSpan.className = 'alert alert-info';
  progSpan.setAttribute('style', 'display:block; color:blue;'); 
  var progGlyph = document.createElement('span');
  progGlyph.className = 'glyphicon glyphicon-time';
  var progText = document.createTextNode(' Submitting your appointment request...');
  progSpan.appendChild(progGlyph);
  progSpan.appendChild(progText);
  allMessagesDiv.appendChild(progSpan);
  
  var appointmentInfo = getInput();
  if(validateInput(appointmentInfo)){
    convertTimeForSQL(appointmentInfo);
    createRequest(appointmentInfo);
  }
  return false;
}

function convertTimeForSQL(a){
  var regex12 = /^(\d{1,2}):(\d{2})([ap]m)+$/;
  if(a.timeSlot.match(regex12)) { 
    var hourInt = Number(a.timeSlot.match(/^(\d+)/)[1]);
    var minInt = Number(a.timeSlot.match(/:(\d+)/)[1]);
    var suffix = a.timeSlot.match(/([ap]m)+$/)[1];
    if (suffix == "pm" && hourInt < 12) hourInt+=12;
    if (suffix == "am" && hourInt == 12) hourInt-=12;
    var hourStr = hourInt.toString();
    var minStr = minInt.toString();
    if (hourInt < 10) hourStr = "0" + hourStr;
    if (minInt < 10) minStr = "0" + minStr;
    a.timeSlot = hourStr + ":" + minStr;
  }
}

function getInput(){
  var input = {};
  input.date = document.getElementById("aDate").value;
  input.timeSlot = document.getElementById("aTime").value;
  if(document.getElementById("aDoctor").selectedIndex==0){
    input.doctor = ''; //null string
  } else if(document.getElementById("aDoctor").selectedIndex==1){
    input.doctor = 'Brewster, Kate NP (Nurse Practitioner)'; //Brewster,K
  } else if(document.getElementById("aDoctor").selectedIndex==2) {
    input.doctor = 'Connor, John MD (Family Practice Physician)'; //Connor,J
  } else if(document.getElementById("aDoctor").selectedIndex==3) {
    input.doctor = 'Connor, Sarah RD (Registered Dietician)'; //Connor,S
  } else if(document.getElementById("aDoctor").selectedIndex==4) {
    input.doctor = 'Reese, Kyle DC (Chiropractic Doctor)';
  }
  input.reason = document.getElementById("aReason").value;
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

function errorRequestingAppt(errString){
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

function validateInput(a){
  clearErrorMessages();
  var validMonth = validDay = validYear = valid12Hour = valid24Hour = validMinute = validDoctor = validReason = false;
  var dateRegex = /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/;
  var timeRegex = /^(\d{1,2}):(\d{2})([ap]m)?$/;
  if(/^\S*$/.test(a.date)) { //cannot be empty and no in-between white spaces allowed
    if(dateArray = a.date.match(dateRegex)) {
      if(dateArray[1]>0 && dateArray[1]<13) { //verify month
        validMonth=true;
      } else {
        errorRequestingAppt(' ERROR: Invalid month. Month must be between 1 and 12');
      }
      if(dateArray[2]>0 && dateArray[2]<32){ //validate day
        validDay=true;
      } else {
        errorRequestingAppt(' ERROR: Invalid day. Day must be between 1 and 31');
      }
      if(dateArray[3]>2014 && dateArray[3]<2021){
        validYear=true;
      } else{
        errorRequestingAppt(' ERROR: Invalid year. Year of appointment must be between 2015 and 2020')
      }
    } 
    else{
      errorRequestingAppt(' ERROR: Appointment date does not match the required format mm/dd/yyyy.');
    }
  } else{
    errorRequestingAppt(' ERROR: Appointment date cannot contain spaces.');
  }
  if(/^\S*$/.test(a.timeSlot)){ 
    if(timeArray = a.timeSlot.match(timeRegex)) {
      if(timeArray[3]){ //am or pm field filled out and matches regex
        if(timeArray[1]<1 || timeArray[1]>12){
          errorRequestingAppt(' ERROR: Appointment time does not match the 24-hour clock format. Please either a time in the required format, e.g. 20:39 or 8:30pm');
        } else {
          valid12Hour=true;
        }
      } else{ //24hr time
        if(timeArray[1]<0 || timeArray[1]>23){
          errorRequestingAppt(' ERROR: Appointment time does not match the 24-hour clock format. Please either a time in the required format, e.g. 20:39 or 8:30pm');
        } else {
          valid24Hour=true;
        }
      }
      if(timeArray[2]<0 || timeArray[2]>59){
        errorRequestingAppt(' ERROR: Appointment minutes must be between 0 and 59.');
      } else {
        validMinute=true;
      }
    } else{
      errorRequestingAppt(' ERROR: Appointment time does not match the required format, e.g. 20:39 or 8:39pm');
    }
  } else{
    errorRequestingAppt(' ERROR: Appointment date cannot contain spaces.');
  }
  if(a.doctor==''){
    errorRequestingAppt(' ERROR: You must select a healthcare provider.');
  } else{ 
    validDoctor=true;
  }
  if(/\S/.test(a.reason)) { //contains at least one char, so not empty or whitespace 
    validReason = true;
  } else{
    errorRequestingAppt(' ERROR: You must enter a reason for your appointment.');
  }
  return (validMonth && validDay && validYear && (valid12Hour || valid24Hour) && validMinute && validDoctor && validReason)? true:false;
}

function createRequest(a){
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
  httpRequest.open('POST','http://web.engr.oregonstate.edu/~watsokel/cs290/finalProject/request.php',true);
  httpRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  var postParameters = 'date='+a.date+'&time='+a.timeSlot+'&doctor='+a.doctor+'&reason='+a.reason+'&approved='+0;
  httpRequest.send(postParameters);
}

function processResponse(){
  try{
    console.log(httpRequest.readyState);
    if(httpRequest.readyState===4 && httpRequest.status===200){
      var response = JSON.parse(httpRequest.responseText);
      debugger;
      pendingAppointmentVerdict(response);
    }else console.log('Problem with the request');
  }
  catch(e){
    console.log('Caught Exception: ' + e);
  }
}

function pendingAppointmentVerdict(r){
  clearErrorMessages();
  if(r.success){
    var successSpan = document.createElement('span');
    successSpan.className = 'alert alert-success';
    successSpan.setAttribute('style', 'display:block; color:green;'); 
    var successGlyph = document.createElement('span');
    successGlyph.className = 'glyphicon glyphicon-ok-circle';
    var successText = document.createTextNode(' Appointment request successfully submitted! ');
    var successAnchor = document.createElement('a');
    successAnchor.setAttribute('href','patientMain.php');
    successAnchor.innerText = 'Click here to view all of your pending appointments';
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
    var failText = document.createTextNode(' Sorry, that appointment has already been requested and is unavailable. Please enter a different date/time.');
    failSpan.appendChild(failGlyph);
    failSpan.appendChild(failText);
    allMessagesDiv.appendChild(failSpan);
  }
}