<!--
/*
#******************************************************************************
#                      Authorize.net Payment Terminal Wordpress
#
#	Author: Convergine.com
#	http://www.convergine.com
#	Version: 1.3
#	Released: December 16, 2014
#
#******************************************************************************
*/
-->
<script type="text/JavaScript">
<!--
	function checkForm() {
		var err=0;
	
		for (var i=0; i < document.ff1.cctype.length; i++){
		   if (document.ff1.cctype[i].checked){
			  var cctype = document.ff1.cctype[i].value;
			}
		}
		<?php
		$reqFields=array(
			"fname",
			"lname",
			"address",
			"city",
			"state",
			"zip",
			"email"
		);
		
		$reqFields_cc=array(
			"ccn",
			"ccname",
			"exp1",
			"exp2",
			"cvv"
		);
		
		if($show_services)
		{
			$reqField[] = "service";
		}
	
		foreach ($reqFields as $v)
		{
			?>
			if (document.getElementById('<?php echo $v;?>').value==0) {
				if (err==0) {
					document.getElementById('<?php echo $v;?>').focus();
				}
				document.getElementById('<?php echo $v;?>').style.backgroundColor='#ffa5a5';
				err=1;
			}
			<?php
		}
		?>
		if(cctype!="PP")
		{
			<?php
			foreach ($reqFields_cc as $v)
			{
				?>
				if (document.getElementById('<?php echo $v;?>').value==0)
				{
					if (err==0)
					{
						document.getElementById('<?php echo $v;?>').focus();
					}
					document.getElementById('<?php echo $v;?>').style.backgroundColor='#ffa5a5';
					err=1;
				}
				<?php
			}
			?>
			if(err==0)
			{
				//check credit card.
				var ccn = document.getElementById("ccn").value;
				if(!isValidCardNumber(ccn)){
					alert("Invalid credit card number. Please check your input and try again");
					return false;
				}
				if(isExpiryDate(document.getElementById("exp2").value,document.getElementById("exp1").value)==false){
					alert("Credit Card expiry date is in the past! Please adjust your input.");
					return false;
				}
		
				if(!isCardTypeCorrect(ccn,cctype)){
					alert("Invalid credit card number/type combination. Please check your input and try again");
					return false;
				}
			}
		}
		var reg1 = /(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/; // not valid
		var reg2 = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,5}|[0-9]{1,3})(\]?)$/; // valid
		if (document.getElementById('email').value==0 || !reg2.test(document.getElementById('email').value))
		{
			if (err==0)
			{
				document.getElementById('email').focus();
			}
			document.getElementById('email').style.backgroundColor='#ffa5a5';
			err=1;
		}
	
		if (err==0)
		{
			return true;
		}
		else
		{
			alert("Please complete all highlighted fields to continue.");
			return false;
		}
	}
	
	function checkFieldBack(fieldObj)
	{
		if (fieldObj.value!=0)
		{
			fieldObj.style.backgroundColor='#F8F8F8';
		}
	}
	function noAlpha(obj)
	{
		reg = /[^0-9.,]/g;
		obj.value =  obj.value.replace(reg,"");
	}
    function checkCaptcha(e) {
        //console.log("Ok");
        jQuery(".submit-btn input").removeAttr("disabled");
        jQuery("#ff1").attr("onsubmit","return checkForm();")
    }
-->
</script>