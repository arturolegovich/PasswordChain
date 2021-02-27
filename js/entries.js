function createTarget(t, url)
{
    newin = window.open("about:blank", t);
    return true;
}
function copy_clip(copyText)
{
    if (window.clipboardData) {
        window.clipboardData.setData("Text", copyText);
    } else if (window.netscape) {
	    /*
		 * You have to sign the code to enable this or allow the action
		 * in about:config by changing
		 * user_pref("signed.applets.codebase_principal_support", true);
		 */
      	netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
		var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
		if (!str) return false;
		str.data=copyText;
		var trans = Components.classes["@mozilla.org/widget/transferable;1"].createInstance(Components.interfaces.nsITransferable);
		if (!trans) return false;
		trans.addDataFlavor("text/unicode");
		trans.setTransferData("text/unicode",str,copyText.length*2);
		var clipid=Components.interfaces.nsIClipboard;
		var clip = Components.classes["@mozilla.org/widget/clipboard;1"].getService(clipid);
		if (!clip) return false;
		clip.setData(trans,null,clipid.kGlobalClipboard);
  	}
    return false;
}