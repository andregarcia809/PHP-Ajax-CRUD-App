$(document).ready(function() {
	$('#addNew').on('click', function() {
		$('#tableManager').modal('show');
	});

	// Default modal after view or edit button is click on
	$('#tableManager').on('hidden.bs.modal', function() {
		$('.showContent')
			.addClass('d-none')
			.removeClass('d-block')
			.fadeOut();
		$('.editContent').fadeIn();
		$('#editRowID').val(0);
		$('#countryName').val('');
		$('#shortDesc').val('');
		$('#longDesc').val('');
		$('#manageBtn')
			.attr('value', 'Add New')
			.attr('onclick', "manageData('addNew')");
	});

	getExistingData(0, 1);
});

function viewOrEdit(rowID, type) {
	$.ajax({
		url: 'ajax.php',
		method: 'POST',
		dataType: 'json',
		data: {
			key: 'getRowData',
			rowID: rowID
		},
		success: function(response) {
			if (type === 'view') {
				$('.editContent').fadeOut();
				$('.showContent')
					.addClass('d-block')
					.fadeIn();
				$('#shortDescView').html(response.shortDesc);
				$('#longDescView').html(response.longDesc);
				$('#manageBtn')
					.attr('value', 'Close')
					.removeClass('btn-success')
					.addClass('btn-primary')
					.on('click', function() {
						$('#tableManager').modal('hide');
					});
			} else {
				$('.showContent').fadeOut();
				$('#editRowID').val(rowID);
				$('.editContent').fadeIn();
				$('#countryName').val(response.countryName);
				$('#shortDesc').val(response.shortDesc);
				$('#longDesc').val(response.longDesc);
				$('#manageBtn')
					.attr('value', 'Save Changes')
					.removeClass('btn-primary')
					.addClass('btn-success')
					.attr('onclick', "manageData('updateRow')");
			}
			$('.modal-title').html(response.countryName);
			$('#tableManager').modal('show');
		}
	});
}

function deleteRow(rowID) {
	var countryName = $('#rowID_' + rowID).attr('data-countryName');
	$.ajax({
		url: 'ajax.php',
		method: 'POST',
		dataType: 'text',
		data: {
			key: 'deleteRow',
			rowID: rowID,
			countryName: countryName
		},
		success: function(response) {
			if (confirm('Are you sure you want to delete ' + countryName + '?')) {
				$('#country_' + rowID)
					.parent()
					.remove();
				alert(response);
			} else {
				alert('Sometimes is good not to delete!');
			}
		}
	});
}

function getExistingData(start, limit) {
	$.ajax({
		url: 'ajax.php',
		method: 'POST',
		dataType: 'text',
		data: {
			key: 'getExistingData',
			start: start,
			limit: limit
		},
		success: function(response) {
			if (response != 'reachedMax') {
				$('tbody').append(response);
				start += limit;
				getExistingData(start, limit);
			} else {
				$('.table').DataTable();
			}
		}
	});
}

// validate Fields
function isNotEmpty(caller) {
	if (caller.val() == '') {
		caller.css('border', ' 1px solid red');
		return false;
	} else {
		caller.css('border', '');
		return true;
	}
}

function manageData(key) {
	var countryName = $('#countryName');
	var shortDesc = $('#shortDesc');
	var longDesc = $('#longDesc');
	var editRowID = $('#editRowID');

	if (isNotEmpty(countryName) && isNotEmpty(shortDesc) && isNotEmpty(longDesc)) {
		// validated make Ajax call
		$.ajax({
			url: 'ajax.php',
			method: 'POST',
			dataType: 'text',
			data: {
				key: key,
				countryName: countryName.val(),
				shortDesc: shortDesc.val(),
				longDesc: longDesc.val(),
				rowID: editRowID.val()
			},
			success: function(response) {
				if (response.includes('exists')) {
					alert(response);
				} else {
					$('#country_' + editRowID.val()).html(countryName.val());
					$('#countryName').val('');
					$('#shortDesc').val('');
					$('#longDesc').val('');
					$('#manageBtn')
						.attr('value', 'Save')
						.attr('onclick', "manageData('addNew')");
					alert(response);
					$('#tableManager').modal('hide');
				}
			}
		});
	}
}
