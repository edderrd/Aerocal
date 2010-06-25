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
            paneClass: 'west-pane',
            resizerClass: 'west-resizer',
            togglerClass: 'ui-layout-toggler',
            buttonClass: 'ui-layout-button'
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
    $(".side-pane > h1").click(
        function()
        {
            $(this).siblings("ul").slideToggle();
        }
    );

}

/**
 * Parse json form and content element from a json into a html string
 */
function parseJsonContent(data)
{
    var rv = Array();
    if (data.form)
    {
        rv.push('<form method="'+data.form.method+'" id="'+data.form.id+'">');
        rv.push("<dl>");
        $.each(data.form.elements, function(elementName, formElement)
        {
            switch (formElement.type)
            {
                case "Zend_Form_Element_Hidden":
                    rv.push(formElement.html);
                    break;
                default:
                    rv.push("<dt>"+formElement.label+"</dt>");
                    rv.push("<dd>"+formElement.html+"</dd>");
                    break;
            }
        });
        rv.push("</dl>");
        rv.push('</form>');
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
                   $rv[name] = function() {dialogElement.dialog("close")};
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
                               alert(data.message);
                               if (data.redirect)
                                   window.location = data.redirect;
                               else
                                   dialogElement.dialog("close");
                           }
                       });
                   };
                   break;
               default:
                   $rv[name] = function() {alert("No action selected")};
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
    $.ajax(
    {
        url: loadUrl,
        type: "post",
        dataType: "json",
        data: params,
        success: function(data)
        {
            $dialog = $("<div id='modal-dialog'>");
            buttons = parseJsonButtons(data.buttons, $dialog);
            $dialog.dialog(
                {
                    title: data.title,
                    show: "drop",
                    hide: "drop",
                    autoOpen: true,
                    modal: true,
                    closeOnEscape: true,
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
                .html(parseJsonContent(data));
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