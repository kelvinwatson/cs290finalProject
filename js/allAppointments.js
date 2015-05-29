var approvedTableDiv;
var pendingTableDiv;

window.onload = displayAllAppointments;

function displayAllAppointments(){
  createRequest();
  //displayApprovedAppointments();
  displayPendingAppointments();

/*<div class="alert alert-info">
  <span class="glyphicon glyphicon-info-sign"></span> You do not have any approved appointments.
</div>
<div class="alert alert-info">
  <span class="glyphicon glyphicon-info-sign"></span> Note: all requested appointments must be approved by a medical office assistant. Please check back again later. 
</div>
*/
}

function createRequest(){
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

function clearPendingAppointmentsTable(){
  if(document.getElementById('pendingTableDiv')){
    removeElement(document.getElementById('pendingTableDiv'));
  }
  pendingTableDiv = document.createElement('div');
  pendingTableDiv.id = 'pendingTableDiv';
  document.getElementById('pendingTableContainer').appendChild(pendingTableDiv); 
}

function displayPendingAppointments(r){
  //if element already exists, delete it and create new, or replace Child
  clearPendingAppointmentsTable();

//if there are appointments, make sure you display this disclaimer <p>These appointments are still pending approval by a medical office assistant. Please check back later.</p>

//if no pending appointments, display this:
//<div class="alert alert-info">
//    <span class="glyphicon glyphicon-info-sign"></span> You do not have any pending appointments.
//  </div>
}

/*AJAX CALL SHOULD  grab ALL APPOINTMENTS, approve and not approved. When printing table in JS, filter them into either table!*/

/**UTILITY FUNCTION**/
function removeElement(node){
  node.parentNode.removeChild(node);
}
