var serviceUrl = '../service'; 

jQuery('document').ready(function($){
	$('div.alert').hide();
	$('#phone, #modal-phone').mask('(99)99999-9999');
	
	$('#addContact').click(function(){
		validateNewContact();
	});

	getContact();
});

function getContact(){
	$.ajax({
	    url: serviceUrl + "/list",
	    type: 'GET',
	    success: function(result) {
	    	if(result.length > 0){
                console.log(result);
	    		populateContactsList(result);
	    	}
	    }, fail: function () {
            failMessage("Erro ao tentar buscar lista de usuários");
        }
	});
}

function addContact(contact){
	$.ajax({
	    url: serviceUrl + "/insert",
	    type: 'POST',
	    data: {contact: contact},
	    success: function(result) {
	    	successMessage("Contato inserido com sucesso!");
		    appendContact(result[0]);
	    	$('#name, #phone, #email').val('');
	    }
	});
}

function deleteContact(id){
    var r = confirm("Tem certeza que deseja deletar esse contato ?");
    if (r == true) {
        $.ajax({
            url: serviceUrl + '/delete/'+ id,
            type: 'DELETE',
            success: function(result) {
                successMessage("Contato removido com sucesso!");
                $('table tbody tr#contact-'+id).remove();
            }
        });
    }
}

function editContact(contact) {
    $('#modal-id').val(contact.id);
	$('#modal-name').val(contact.name);
	$('#modal-phone').val(contact.phone);
	$('#modal-email').val(contact.email);

	$('.modal').modal('show');
}

function updateContactInService() {
    var name = $("#modal-name").val();
    var phone = $("#modal-phone").val();
    var email = $("#modal-email").val();

    if(name.length > 3 && phone.length > 6){
        var updatedContact = {
            id: $('#modal-id').val(),
            name: name,
            phone: phone,
            email: email,
        };

        $.ajax({
            url: serviceUrl + '/edit/'+ updatedContact.id,
            type: 'POST',
            data: {contact: updatedContact},
            success: function(result) {
                console.log(result);
                $('.modal').modal('hide');
                getContact();
                successMessage("Contato editado com sucesso!");
            }
        });


    }
}

function populateContactsList(contacts){
    $('table tbody').empty();
	$( contacts ).each(function(index, contact) {
		appendContact(contact);
	});
}

function validateNewContact(){
	var name = $("#name").val();
	var phone = $("#phone").val();
	var email = $("#email").val();
	
	if(name.length > 3 && phone.length > 6){
		var contact = {name: name, phone: phone, email: email};
		addContact(contact);
	} 
}

function appendContact(contact){
	var row =
		"<tr id='contact-"+contact.id+"'>"+
			"<td class='id'>"+contact.id+"</td>"+
			"<td class='name'>"+contact.name+"</td>"+
			"<td class='phone'>"+contact.phone+"</td>"+
			"<td class='email'>"+contact.email+"</td>"+
			"<td><button type='button' class='btn btn-primary' onclick='editContact("+ JSON.stringify(contact) +")'>Editar</td>"+
			"<td><button type='button' class='btn btn-danger' onclick='deleteContact("+contact.id+")'>Deletar</td>";
		"</tr>";
	$('table tbody').append(row);
}

function successMessage(message){
	$('div.alert').html('<strong>'+message+'</strong>');
	$('div.alert').removeClass('alert-danger');
	$('div.alert').addClass('alert-success');
	fadeMessage();
}

function failMessage(message){
	$('div.alert').html('<strong>'+message+'</strong>');
	$('div.alert').removeClass('alert-success');
	$('div.alert').addClass('alert-danger');
	fadeMessage();
}

function fadeMessage(){
	$('div.alert').fadeIn();
	setTimeout(function(){ $('div.alert').fadeOut(); }, 3000);
}
