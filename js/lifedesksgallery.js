$(function() {
  $('.lifedesks_info span').bt({
	  positions: 'top',
	  contentSelector: "$(this).parent().next().html()",
	  trigger: 'click',
	  width: 220,
	  centerPointX: .9,
	  spikeLength: 65,
	  spikeGirth: 40,
	  padding: 15,
	  cornerRadius: 25,
	  fill: '#FFF',
	  strokeStyle: '#ABABAB',
	  strokeWidth: 1
  });
  $('#site-sort-select').change(function() {
  	var myform = $(this).parent();
	if ($(this).val() != "") {
		myform.submit();
	}
  });
});