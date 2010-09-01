/**
 * Create a layout using jquery.layout plugin
 */
function applyLayout()
{
    return $('body').layout(
    {
        defaults:
        {
            applyDefaultStyles: true,
            center__onresize: "innerLayout.resizeAll"            
        },
        north:
        {
            closable: false,
            resizable: false,
            size: '50',
            spacing_open: 0,
            applyDefaultStyles: false,
            paneClass: 'north-pane'
        },
        center:
        {
            applyDefaultStyles: false,
            paneClass: 'center-pane'
        },
        west:
        {
            applyDefaultStyles: false,
            closable: false,
            resizable: false,
            paneClass: 'west-pane',
            size: '200',
            spacing_open: 0
        },
        east:
        {
            //TODO: Add options here
        },
        south:
        {
            initClosed: true
        }

    });
}

/**
 * Effects on navigation panels
 */
function navigationPanels()
{
    $(".side-pane > h1").css("cursor", "pointer");
    $(".side-pane > h1").toggle(function(e) {
    		$(this).prev().hide();
    		$(this).next().slideUp();
    		$(this).prev().removeClass("toggle-normal");
    		$(this).prev().addClass("toggle-down");
    		$(this).prev().fadeIn();
        },
        function(e) {
        	$(this).prev().hide();
        	$(this).next().slideDown();
    		$(this).prev().removeClass("toggle-down");
    		$(this).prev().addClass("toggle-normal");
    		$(this).prev().fadeIn();
        }
    );

}

/**
 * Parse json form and content element from a json into a html string
 */
function parseJsonContent(data, $dialog)
{
    var rv = Array();
    if (data.form)
    {
        $dialog.html("");
        var $form = $('<form class="modal-form" method="'+data.form.method+'" id="'+data.form.id+'">');
    	var $table = $("<table></table>");
        
        $.each(data.form.elements, function(elementName, formElement)
        {
            switch (formElement.type)
            {
                case "Zend_Form_Element_Hidden":
                    rv.push(formElement.html);
                    var $hidden = $("<span></span>").html(formElement.html);
                    $form.append($hidden);
                    break;
                default:
                    var $row = $("<tr>");
                    var $cellLabel = $("<td>").html(formElement.label);
                    var $cellInput = $("<td>").html(formElement.html);
                    $row.append($cellLabel)
                        .append($cellInput);
                    $table.append($row);
                    break;
            }
        });
        $form.append($table);
        $dialog.append($form);
    }
    if (data.content)
    {
        rv.push('<div id="'+data.content.id+'"');
        $.each(data.content.elements, function(elementName, contentElement)
        {
            rv.push(contentElement.html);
        });
    }

    return rv.join("\n");
}

function parseJsonButtons(buttons, dialogElement)
{
    $rv = Object();
    if(buttons)
    {
        $.each(buttons, function(name, button)
        {
            switch(button.action)
            {
                case 'close':
                    $rv[name] = function() {
                        dialogElement.dialog("close")
                        };
                    break;
                case 'submit':
                    $rv[name] = function()
                    {
                        var data = dialogElement.children("form").serialize();
                        $.ajax(
                        {
                            url: button.url,
                            data: data,
                            success: function(data)
                            {
                                if (data.form && !data.isValid)
                                {
                                    parseJsonContent(data, dialogElement);
                                }
                                else
                                {
                                    alert(data.message);
                                    if (data.redirect)
                                        window.location = data.redirect;
                                    else
                                        dialogElement.dialog("close");
                                }
                            }
                        });
                    };
                    break;
                default:
                    $rv[name] = function() {
                        alert("No action selected")
                        };
                    break;
            }
        });
    }

    return $rv;
}

/**
 * Create a modal dialog with parsed json data
 */
function modalDialog(loadUrl, params, callbacks)
{
	params = params == undefined ? new Object() : params;
	callbacks = callbacks == undefined ? new Object() : callbacks;
	
    $.ajax(
    {
        url: loadUrl,
        type: "post",
        dataType: "json",
        data: params,
        success: function(data)
        {
            var $dialog = null;
            if ($("#modal-dialog"))
            {
                $dialog = $("#modal-dialog");
                $dialog.html("");
            }

            $dialog = $("<div id='modal-dialog'>");
            parseJsonContent(data, $dialog);

            buttons = parseJsonButtons(data.buttons, $dialog);
            $dialog.dialog(
                {
                    title: data.title,
                    show: "drop",
                    hide: "drop",
                    modal: true,
                    closeOnEscape: true,
//                    position: ["center", "top"],
                    close: function()
                    {
                        $.each(callbacks, function(name, func)
                        {
                            func();
                        });
                        $("#modal-dialog").remove();
                    },
                    buttons: buttons
                })
                .show();
        }
    });
}

/**
 * Date format to match mysql style
 * @param Date date1
 * @return string
 */
function formatDate(date1) {
    return date1.getFullYear() + '-' +
        (date1.getMonth() < 9 ? '0' : '') + (date1.getMonth()+1) + '-' +
        (date1.getDate() < 10 ? '0' : '') + date1.getDate() + " " +
        date1.getHours()+':'+date1.getMinutes();
}

/**
 * Convert alerts into a nice looking notifications
 */
function consumeAlert(title)
{
    if (_alert) return;
    _alert = window.alert;
    window.alert = function(message) {
        $.pnotify({
            pnotify_title: title,
            pnotify_text: message,
            pnotify_history: false
        });
    };
}

/**
 * Display multiple notifications
 * @params Object messages array of messages
 */
function showNotifications(title, messages)
{
    $.each(messages, function(index, message)
    {
        $.pnotify({
            pnotify_title: title,
            pnotify_text: message,
            pnotify_history: false
        })
    });
}

/**
 * Creates a button toolbar
 * Depends on jquery ui 1.8+ buttons
 * @param string elementId
 */
function createToolbar(elementId)
{
    $("#"+elementId).buttonset();
}

/**
 * Change calendar view
 * Depends on jquery ui 1.8+ buttons
 * and Calendar plugin
 * @param string calendarId
 * @param JQueryElement $element
 * @param string action
 */
function changeCalendarView(calendarId, $element, action)
{
    $("#"+calendarId).swtichView(action);
    $element.button("enable");
}

function tableHightlight(elementId)
{
	var lookupElements = "#"+elementId+" tr";
	
	$(lookupElements).hover(
            function() {
                $(this).children("td").each(function() {
                    $(this).children("a.icon").removeClass("x-hidden");
                });
            },
            function() {
                $(this).children("td").each(function() {
                    $(this).children("a.icon").addClass("x-hidden");
                });
            }
        );
}

/**
 * Retrieve unread messages
 */
function getMessages(loadUrl)
{
    $.ajax({
        url: loadUrl,
        type: "post",
        dataType: "json",
        success: function(response) {
            if (response.messages)
                $("#msg-counter").html(response.messages.count);

            if (response.notification)
            {
                $.each(response.notification, function(index, message) {
                    $.pnotify({
                        pnotify_title: response.notificationTitle,
                        pnotify_text: message,
                        pnotify_history: false
                    });
                })
            }
        }
    });
}