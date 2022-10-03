// listen to payment section added:
eventBus.$on('after-checkout-payment-section-added', function(){
	$(document).ready(() => {

	// Get the modal
		var modal = $("#clearentModal");

	// Get the button that opens the modal
		var btn = $("#open-clearent-modal");
		var close_btn = $('#close_modal');

	// Get the <span> element that closes the modal
		var span = $(".close")[0];
		span  = $(span);

	// When the user clicks on the button, open the modal
		btn.on('click', () => {
			modal.css('display','block');
		});
		span.on('click', () => {
			modal.css('display', 'none');
		});
	// When the user clicks on <span> (x), close the modal
		close_btn.on('click', () => {
			modal.css('display', 'none');
		});

	// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			var id = $(event.target).attr('id');
			if (id === 'clearentModal') {
				modal.css('display', 'none');
			}
		}
	});
});
