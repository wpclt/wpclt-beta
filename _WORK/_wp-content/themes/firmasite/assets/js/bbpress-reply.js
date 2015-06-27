addReply = {
    moveForm : function(replyId, parentId, respondId, postId) {
        var t = this;
 
        // Remove editor if necessary
        t.red();
 
        var div,
        reply = t.I(replyId),
        respond = t.I(respondId),
        cancel = t.I('bbp-cancel-reply-to-link'),
        parent = t.I('bbp_reply_to'), post = t.I('bbp_topic_id');
 
        if ( ! reply || ! respond || ! cancel || ! parent)
                return;
 
        t.respondId = respondId;
        postId = postId || false;
 
        if ( ! t.I('bbp-temp-form-div') ) {
                div = document.createElement('div');
                div.id = 'bbp-temp-form-div';
                div.style.display = 'none';
                respond.parentNode.insertBefore(div, respond);
        }
 
        reply.parentNode.insertBefore(respond, reply.nextSibling);
 
        if ( post && postId )
            post.value = postId;
        parent.value = parentId;
        cancel.style.display = '';
 
        // Add editor if necessary
        t.aed();
 
        cancel.onclick = function() {
            var t = addReply;
 
            // Remove editor if necessary
            t.red();
 
            var temp = t.I('bbp-temp-form-div'), respond = t.I(t.respondId);
 
            if ( ! temp || ! respond )
                return;
 
            t.I('bbp_reply_to').value = '0';
            temp.parentNode.insertBefore(respond, temp);
            temp.parentNode.removeChild(temp);
            this.style.display = 'none';
            this.onclick = null;
 
            // Add editor if necessary
            t.aed();
 
            return false;
        }
 
        return false;
    },
    I : function(e) {
        return document.getElementById(e);
    },
    red : function() {
        /* TinyMCE defined means wp_editor has Visual or both Visual and HTML/Text editors enabled
         * If editor is in HTML editor only mode our work here is unnecessary
         */
        if(typeof(tinyMCE) == 'undefined')
            return;
 
        var tmce = tinyMCE.get('bbp_reply_content');
        if (tmce && !tmce.isHidden()){
            /* Remove TinyMCE from textarea if necessary
             * and mark the current editor tab as Visual
             */
            this.mode = 'tmce';
            tmce.remove();
        }else{
            /* Html editor can be moved in DOM without removal
             * so we just mark current editor tab as Html
             */
            this.mode = 'html';
        }
    },
    aed : function() {
        if(typeof(tinyMCE) == 'undefined')
            return;
 
        if (this.mode == 'tmce'){
            /* Add Visual editor to textarea using code from wp-includes/js/editor.js
             * enqueued by _WP_Editors PHP class whenever Visual editor is enabled.
             * This code switches to Visual editor with id 'bbp_reply_content'
             */
            switchEditors.go('bbp_reply_content', 'tmce');
        }else if (this.mode == 'html'){
            /* Add HTML/Text editor to textarea using code from wp-includes/js/editor.js
             * enqueued by _WP_Editors PHP class whenever Visual editor is enabled.
             * If Visual editor is not enabled 'return' above makes this code off limits.
             * This code switches to HTML editor with id 'bbp_reply_content'
             */
            switchEditors.go('bbp_reply_content', 'html');
        }
    }
}