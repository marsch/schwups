

require('monaco-editor/min/vs/editor/editor.main.css')
require('jqueryfiletree/dist/jQueryFileTree.min.css')

import './styles/customizer.css';

const jQuery = require('jquery')
window.jQuery = jQuery
const ftree = require('jqueryfiletree')

import * as monaco from 'monaco-editor';

self.MonacoEnvironment = {
	getWorkerUrl: function (moduleId, label) {
		if (label === 'json') {
			return '/build/json.worker.js';
		}
		if (label === 'css' || label === 'scss' || label === 'less') {
			return '/build/css.worker.js';
		}
		if (label === 'html' || label === 'handlebars' || label === 'razor') {
			return '/build/html.worker.js';
		}
		return '/build/editor.worker.js';
	}
};


const model = monaco.editor.createModel(
    '',
    ''
);

const editor = monaco.editor.create(document.getElementById('monaco-editor'), {
	model: model,
    roundedSelection: false,
	scrollBeyondLastLine: false,
	readOnly: false,
	theme: "vs-dark",
    minimap: {
        enabled: true
    }
});


// initialize actions

editor.addAction({
    id: 'save-file',
    label: 'Save current File',
    keybindings: [
        monaco.KeyMod.CtrlCmd | monaco.KeyCode.KEY_S,
    ],
    precondition: null,
    keybindingContext: null,

    run: (ed) => {
        const dfd = jQuery.Deferred()
        const filename = ed.getModel().uri
        jQuery.ajax({
            method: 'PUT',
            contentType: "text/plain",
            url: `/customize/${filename}`,
            data:  ed.getValue(),
            processData: false,
            success: () => {
                console.log('saved')
                dfd.resolve()
            },
            error: (err) => {
                console.error(err)
                dfd.reject(err)
            },
            withCredentials: true
        })

        return dfd.promise()
    }
})


const extToType = {
    'js': 'javascript',
    'twig': 'twig',
    'html': 'html',
    'yml': 'yaml',
    'yaml': 'yaml',
    'php': 'php',
    'json': 'json',
    'scss': 'scss'
}

const loadFile = (filename) => {
    filename = filename.substr(1)
    const fileExt = filename.split('.').pop()
    const ftype = extToType[fileExt] ? extToType[fileExt] : 'text'

    jQuery.ajax({
        method: 'GET',
        url: `/customize/${filename}`,
        dataType: 'text',
        withCredentials: true,
        success: (data) => {

            editor.getModel().dispose()
            const model = monaco.editor.createModel(jQuery.trim(data), ftype, filename)
            editor.setModel(model)
        }
    })
}

jQuery(($) => {
    $('a[data-filename]').on('click', (e) => {
        const filename = $(e.target).attr('data-filename')
        loadFile(filename)
    })

    $('#files-view').fileTree({
        script: '/customize-tree',
        expandSpeed: 1000,
        collapseSpeed: 1000,
        multiFolder: false
    })

    $('#files-view' ).on('filetreeclicked', (e, data) => {
        const filepath = data.rel
        console.log('click', filepath)
        loadFile(filepath)
    })
})

setTimeout(() => {
    loadFile('/helloworld.html.twig')
}, 0)





console.log("f4ckin c0stomizr goes here", model)