/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.extraPlugins = 'codesnippet,bgimage';
    config.codeSnippet_theme = 'ir_black';
    config.codeSnippet_languages = {
        xml: 'XML',
        html: 'HTML',
        bash: 'Bash',
        cpp: 'C++',
        java: 'Java',
        php: 'PHP',
        css: 'CSS',
        diff: 'Diff',
        ini: 'INI',
        javascript: 'JavaScript',
        json: 'JSON',
        makefile: 'Makefile',
        markdown: 'Markdown',
        nginx: 'Nginx',
        python: 'Python',
        sql: 'SQL',
        apache: 'Apache'
    };
};
