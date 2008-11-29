var Apps_MailClient = {
preview: function(id,query,msg_id) {
    var iframe = $(id+'_body');
    if(!iframe) return;
    iframe.src = 'modules/Apps/MailClient/preview.php?'+query;
	$('apps_mailclient_msg_'+msg_id).style.fontWeight='normal';
},
progress_bar: {
get: function(parent,name) {
	var progr = $(parent).getElementsByClassName(name)[0];
	if(!progr) {
		var progr = document.createElement('div');
		progr.className = name;
		var progr_t = document.createElement('div');
		progr.appendChild(progr_t);
		var progr_p = document.createElement('div');
		progr_p.className = "mail_progressbar_out";
		progr.appendChild(progr_p);
		var progr_pi = document.createElement('div');
		progr_pi.className = "mail_progressbar_in";
		progr_p.appendChild(progr_pi);
		progr_pi.style.width = 0;
		$(parent).appendChild(progr);
	}
	return progr;
},
set_text: function(parent,name,t) {
	var progr = Apps_MailClient.progress_bar.get(parent,name);
	progr.getElementsByTagName('div')[0].innerHTML = t;
},
set_progress: function(parent,name,p) {
	var progr = Apps_MailClient.progress_bar.get(parent,name);
	var pr = progr.getElementsByTagName('div')[1].firstChild;
	pr.style.width = p+'%';
}
},
check_mail: Class.create({
f: function(me,name) {
	if(this.bind) {
		if($(name).style.display=='block') {
			var today = new Date();
			var ch = document.createElement('iframe');
			ch.id = name+'X';
			ch.src = 'modules/Apps/MailClient/checknew.php?id='+name+'&t='+today.getTime();
			ch.style.display = "none";

			document.body.appendChild(ch);
		} else {
			setTimeout(this.bind,100);
		}
	}
},
bind:null,
initialize: function(name) {
	if(this.destroy_bind) {
		this.destroy_bind();
	}
	this.bind = this.f.bindAsEventListener(this,name);
	Event.observe(name+'b','click',this.bind);
	this.destroy_bind = this.destroy_f.bindAsEventListener(this,name);
	document.observe('e:loading',this.destroy_bind);
},
destroy_bind:null,
destroy_f:function(em,name) {
	var x=$(name+'X');
	if(x) x.parentNode.removeChild(x);
	Event.stopObserving(name+'b','click',this.bind);
	delete(this.bind);
	document.stopObserving('e:loading',this.destroy_bind);
	delete(this.destroy_bind);
},
}),
hide:function(name) {
	var x=$(name+'X');
	x.parentNode.removeChild(x);
	leightbox_deactivate(name);
	$(name+'L').style.display='none';
},
show_hide_button:function(name) {
	$(name+'L').style.display='block';
},
actions_set_id:function(id) {
	var href = $('mail_client_actions_view_source').getAttribute('tpl_href');
	$('mail_client_actions_view_source').setAttribute('href',href.replace('__MSG_ID__',id));
	$('mail_client_actions_move_msg_id').value=id;
},
msg_num_cache: Array(),
updating_msg_num: Array(),
update_msg_num: function(applet_id,accid,cache) {
	if(!$('mailaccount_'+applet_id+'_'+accid)) return;
	if(Apps_MailClient.updating_msg_num[accid] == true) {
		setTimeout('Apps_MailClient.update_msg_num('+applet_id+', '+accid+', 1)',1000);
		return;
	}
	Apps_MailClient.updating_msg_num[accid] = true;
	if(cache && typeof Apps_MailClient.msg_num_cache[accid] != 'undefined')
		$('mailaccount_'+applet_id+'_'+accid).innerHTML = Apps_MailClient.msg_num_cache[accid];
	else 
		new Ajax.Updater('mailaccount_'+applet_id+'_'+accid,'modules/Apps/MailClient/refresh.php',{
			method:'post',
			onComplete:function(r){
				Apps_MailClient.msg_num_cache[accid]=r.responseText;
				Apps_MailClient.updating_msg_num[accid] = false;
			},
			parameters:{acc_id:accid}});
}
};

