function tinySetup(config) {
    if (typeof tinyMCE === 'undefined') {
        setTimeout(function () {
            tinySetup(config);
        }, 100);
        return;
    }

    var cssId = 'cssIdFontAwesomePro';  // you could encode the css path itself to generate id..
    if (!document.getElementById('cssIdFontAwesomePro')) {
        var head = document.getElementsByTagName('head')[0];
        var link = document.createElement('link');
        link.id = cssId;
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = '//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';
        link.media = 'all';
        head.appendChild(link);
    }

    $("<style>").prop("type", "text/css").html('.mce-i-charmap::before {font-family: fontawesome; content: "\\f155";}').appendTo("head");
    $("<style>").prop("type", "text/css").html('.mce-i-hr::before {font-family: fontawesome; content: "\\f07e";}').appendTo("head");
    $("<style>").prop("type", "text/css").html('.mce-i-visualblocks::before {font-family: fontawesome; content: "\\f248";}').appendTo("head");

    if (!config)
        config = {};

    var editor_selector = 'rte';
    //if (typeof config['editor_selector'] !== 'undefined')
    //var editor_selector = config['editor_selector'];
    if (typeof config['editor_selector'] != 'undefined')
        config['selector'] = '.' + config['editor_selector'];

    //safari,pagebreak,style,table,advimage,advlink,inlinepopups,media,contextmenu,paste,fullscreen,xhtmlxtras,preview
    default_config = {
        selector: ".rte",
        plugins: "visualblocks, preview searchreplace print insertdatetime, hr charmap colorpicker anchor code link image paste pagebreak table contextmenu filemanager table code media autoresize textcolor emoticons",
        toolbar2: "newdocument,print,|,bold,italic,underline,|,strikethrough,superscript,subscript,|,forecolor,colorpicker,backcolor,|,bullist,numlist,outdent,indent",
        toolbar1: "styleselect,|,formatselect,|,fontselect,|,fontsizeselect,",
        toolbar3: "code,|,table,|,cut,copy,paste,searchreplace,|,blockquote,|,undo,redo,|,link,unlink,anchor,|,image,emoticons,media,|,inserttime,|,preview,visualblocks,|,charmap,|,hr,",

        external_filemanager_path: ad + "/filemanager/",
        filemanager_title: "File manager",
        external_plugins: {"filemanager": ad + "/filemanager/plugin.min.js"},
        extended_valid_elements: 'link[*], pre[*],script[*],style[*],meta[*]',
        valid_children: "+body[meta|style|script|iframe|section|link],pre[iframe|section|script|div|p|br|span|img|style|h1|h2|h3|h4|h5],*[*]",
        valid_elements: '*[*]',
        force_p_newlines: false,
        cleanup: false,
        forced_root_block: false,
        force_br_newlines: true,
        relative_urls: false,
        convert_urls: true,
        remove_script_host: false,

        menu: {
            edit: {title: 'Edit', items: 'undo redo | cut copy paste | selectall'},
            insert: {title: 'Insert', items: 'media image link | pagebreak'},
            view: {title: 'View', items: 'visualaid'},
            format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
            table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
            tools: {title: 'Tools', items: 'code'}
        }

    }

    $.each(default_config, function (index, el) {
        if (config[index] === undefined)
            config[index] = el;
    });

    tinyMCE.init(config);

}

$().ready(function () {
    tinySetup();
});function tinySetup(config) {
    if (typeof tinyMCE === 'undefined') {
        setTimeout(function () {
            tinySetup(config);
        }, 100);
        return;
    }

    var cssId = 'cssIdFontAwesomePro';  // you could encode the css path itself to generate id..
    if (!document.getElementById('cssIdFontAwesomePro')) {
        var head = document.getElementsByTagName('head')[0];
        var link = document.createElement('link');
        link.id = cssId;
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = '//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';
        link.media = 'all';
        head.appendChild(link);
    }

    $("<style>").prop("type", "text/css").html('.mce-i-charmap::before {font-family: fontawesome; content: "\\f155";}').appendTo("head");
    $("<style>").prop("type", "text/css").html('.mce-i-hr::before {font-family: fontawesome; content: "\\f07e";}').appendTo("head");
    $("<style>").prop("type", "text/css").html('.mce-i-visualblocks::before {font-family: fontawesome; content: "\\f248";}').appendTo("head");

    if (!config)
        config = {};

    var editor_selector = 'rte';
    //if (typeof config['editor_selector'] !== 'undefined')
    //var editor_selector = config['editor_selector'];
    if (typeof config['editor_selector'] != 'undefined')
        config['selector'] = '.' + config['editor_selector'];

    //safari,pagebreak,style,table,advimage,advlink,inlinepopups,media,contextmenu,paste,fullscreen,xhtmlxtras,preview
    default_config = {
        selector: ".rte",
        plugins: "visualblocks, preview searchreplace print insertdatetime, hr charmap colorpicker anchor code link image paste pagebreak table contextmenu filemanager table code media autoresize textcolor emoticons",
        toolbar2: "newdocument,print,|,bold,italic,underline,|,strikethrough,superscript,subscript,|,forecolor,colorpicker,backcolor,|,bullist,numlist,outdent,indent",
        toolbar1: "styleselect,|,formatselect,|,fontselect,|,fontsizeselect,",
        toolbar3: "code,|,table,|,cut,copy,paste,searchreplace,|,blockquote,|,undo,redo,|,link,unlink,anchor,|,image,emoticons,media,|,inserttime,|,preview,visualblocks,|,charmap,|,hr,",

        external_filemanager_path: ad + "/filemanager/",
        filemanager_title: "File manager",
        external_plugins: {"filemanager": ad + "/filemanager/plugin.min.js"},
        extended_valid_elements: 'link[*], pre[*],script[*],style[*]',
        valid_children: "+body[style|script|iframe|section|link],pre[iframe|section|script|div|p|br|span|img|style|h1|h2|h3|h4|h5],*[*]",
        valid_elements: '*[*]',
        force_p_newlines: false,
        cleanup: false,
        forced_root_block: false,
        force_br_newlines: true,
        relative_urls: false,
        convert_urls: true,
        remove_script_host: false,

        menu: {
            edit: {title: 'Edit', items: 'undo redo | cut copy paste | selectall'},
            insert: {title: 'Insert', items: 'media image link | pagebreak'},
            view: {title: 'View', items: 'visualaid'},
            format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
            table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
            tools: {title: 'Tools', items: 'code'}
        }

    }

    $.each(default_config, function (index, el) {
        if (config[index] === undefined)
            config[index] = el;
    });

    tinyMCE.init(config);

}

$().ready(function () {
    tinySetup();
});