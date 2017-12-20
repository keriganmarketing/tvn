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
function noAlpha(obj){
	reg = /[^0-9.]/g;
	obj.value =  obj.value.replace(reg,"");
}