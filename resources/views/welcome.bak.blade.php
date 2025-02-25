<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Task Logger</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
        <!--<script src="https://zeptojs.com/zepto.min.js"></script>-->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    </head>
    <body>
        <div>
            <div class="d-flex flex-row justify-content-evenly">
                <div class="col-2">
                    <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark bg-body-tertiary shadow sticky-top" style="width: 280px; min-height: 100vh">
                        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                            <img src="./logo.svg" class="me-2" width="50px" height="50px">
                            <span class="fs-5" style="margin-left: -20px; margin-top: -5px;">Task Logger</span>
                        </a>
                        <br>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item">
                                <a href="#" class="nav-link active" aria-current="page">
                                    <i class="fa-regular fa-clock"></i>
                                    Time Tracker
                                </a>
                            </li>
                            <li>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#tagManager" class="nav-link text-white">
                                    <i class="fa-solid fa-tag"></i>
                                    Tags
                                </a>
                            </li>
                            <li>
                                <a href="#" data-bs-toggle="offcanvas" data-bs-target="#noteManager" class="nav-link text-white">
                                    <i class="fa-solid fa-note-sticky"></i>
                                    Notes
                                </a>
                            </li>
                            <li class="my-2">
                                <b>External Resource</b>
                            </li>
                            <li>
                                <a href="http://localhost/phpmyadmin" target="_blank" class="nav-link text-white">
                                    <i class="fa-solid fa-database"></i>
                                    phpMyAdmin
                                </a>
                            </li>
                            <li>
                                <a href="https://github.com/rondeo-balos" target="_blank" class="nav-link text-white">
                                    <i class="fa-brands fa-github"></i>
                                    Github
                                </a>
                            </li>
                            <li>
                                <a href="https://app.daily.dev/" target="_blank" class="nav-link text-white">
                                    <i class="fa-solid fa-code"></i>
                                    daily.dev
                                </a>
                            </li>
                            <li>
                                <a href="https://one.dash.cloudflare.com" target="_blank" class="nav-link text-white">
                                    <i class="fa-solid fa-shield-halved"></i>
                                    Zero Trust Dash
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-10 p-5">
                    <div class="border mb-5 bg-body-tertiary p-2">
                        <form action="/api/tasks" method="POST" id="taskForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="d-flex flex-row justify-content-between">
                                <div class="col-7">
                                    <input type="text" name="title" class="form-control border-0 rounded-0" placeholder="What are you working on?" required>
                                </div>
                                <input type="hidden" name="description" value="[]">
                                <div class="col-auto">
                                    <button type="button" role="button" class="btn btn-outline-primary border-0 rounded-0" data-bs-toggle="modal" data-bs-target="#modalDescription"><i class="fa fa-list"></i></button>
                                </div>
                                <div class="col-1">
                                    <div class="dropdown tags-dropdown">
                                        <a href="#" class="btn" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside"><i class="fa fa-tag"></i> Tag</a>
                                        <div class="dropdown-menu p-4" style="min-width: 300px"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="start">
                                <input type="hidden" name="end">
                                <div class="col-auto">
                                    <div class="d-flex h-100 align-items-center justify-content-center">
                                        <strong id="timer">00:00:00</strong>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-flex h-100 align-items-center justify-content-center">
                                        <button type="button" role="button" class="btn btn-primary rounded-0" id="start"><i class="fa fa-play"></i> START</button>
                                        <button type="submit" role="submit" class="btn btn-danger rounded-0 d-none" id="end"><i class="fa fa-stop"></i> STOP</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <button type="button" class="btn btn-primary btn-sm ms-auto fw-bold d-block mb-2"><i class="fa-solid fa-floppy-disk"></i> Export</button>

                    <div id="data"></div>
                    
                    <div class="border rounded d-none shadow mb-4 table-template">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <td colspan="500" class="bg-body-secondary">
                                        <div class="d-flex flex-row justify-content-between px-2">
                                            <strong id="date">[Date]</strong>
                                            <span id="dayTotal">TOTAL: <strong>[total]</strong></span>
                                        </div>
                                    </td>
                                </tr>
                            </thead>

                            <tbody></tbody>
                        </table>
                    </div>

                    <!-- Task Description modal -->
                    <div class="modal fade" id="modalDescription" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tasks Description</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <textarea class="description form-control mb-3 d-none"></textarea>
                                    <div class="descriptionWrapper"></div>
                                    <div class="text-end">
                                        <button class="btn btn-outline-primary rounded-0 addDescription"><i class="fa fa-plus-circle"></i> Description</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tags Modal -->
                    <div class="modal fade" id="tagManager" tabindex="-1" aria-labelledby="tagManager" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="tagManager">Tags</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="tagsContainer" class="d-flex flex-wrap gap-2"></div>
                                </div>
                                <div class="modal-footer justify-content-start">
                                    <strong>New Tag</strong>
                                    <form id="newTag" class="d-flex flex-row my-3 gap-3" action="api/tags" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input type="text" name="tag" class="form-control w-100" placeholder="New Tag">
                                        <select name="color" class="form-select">
                                            <option value="light">Select Color</option>
                                            <option value="primary" class="text-primary">Primary</option>
                                            <option value="primary-emphasis" class="text-primary-emphasis">Primary Emphasis</option>
                                            <option value="secondary" class="text-secondary">Secondary</option>
                                            <option value="secondary-emphasis" class="text-secondary-emphasis">Secondary Emphasis</option>
                                            <option value="success" class="text-success">Success</option>
                                            <option value="success-emphasis" class="text-success-emphasis">Success Emphasis</option>
                                            <option value="danger" class="text-danger">Danger</option>
                                            <option value="danger-emphasis" class="text-danger-emphasis">Danger Emphasis</option>
                                            <option value="warning" class="text-warning">Warning</option>
                                            <option value="warning-emphasis" class="text-warning-emphasis">Warning Emphasis</option>
                                            <option value="info" class="text-info">Info</option>
                                            <option value="info-emphasis" class="text-info-emphasis">Info Emphasis</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Off canvas -->
                    <div class="offcanvas offcanvas-end w-50" data-bs-scroll="true" data-bs-backdrop="true" tabindex="-1" id="noteManager" aria-labelledby="noteManagerLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="noteManagerLabel">Notes</h5>
                        </div>
                        <div class="offcanvas-body">
                            <div id="allNotes"></div>
                            <form method="POST" action="api/notes" id="newNote" class="p-1">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input type="hidden" name="index" value="null" />
                                <input type="text" class="border-0 rounded-0 bg-transparent w-100 focus-ring focus-ring-dark" name="content" placeholder="Type anything..." />
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script>
            $( document ).ready(function($){
                const generateTags = function( dropdown, idAfter = '', currVal = 0, callback = function(){} ) {
                    var tagsContainer = $( '#tagsContainer' );

                    $.getJSON( '/api/tags', function(response) {
                        dropdown.empty();
                        tagsContainer.empty();

                        response.forEach(tag => {
                            var input = $( `<input type="radio" class="btn-check" name="tag" data-title="${tag.tag}" data-color="${tag.color}" id="tag-${tag.tag}-${idAfter}" value="${tag.id}" autocomplete="off" required>` );
                            var label = $( `<label class="btn btn-sm btn-outline-${tag.color.replace('-emphasis', '')} m-1" for="tag-${tag.tag}-${idAfter}">${tag.tag}</label>` );
                            dropdown.append( input ).append( label );

                            var editorForm = $( `<form class="flex-shrink-1 w-auto min-w-" action="api/tags/${tag.id}" method="POST">` );
                            var editor = $( `<input type="text" class="btn btn-outline-${tag.color.replace('-emphasis', '')}" style="width: ${tag.tag.length*16}px;" size="1" name="tag" data-title="${tag.tag}" value="${tag.tag}">` );
                            editor.on( 'change', function() {
                                updateData( editorForm, function() {
                                    generateData();
                                });
                            });
                            tagsContainer.append( '<input type="hidden" name="_token" value="{{ csrf_token() }}" />' ).append( editorForm.append( editor ) );
                        });

                        callback();
                    });
                }
                var dropdown = $( '.dropdown.tags-dropdown .dropdown-menu' );
                generateTags( dropdown );

                $( document ).on( 'change', '[name="tag"]', function(){
                    var title = $(this).attr( 'data-title' );
                    var color = $(this).attr( 'data-color' );
                    $(this).closest( '.dropdown.tags-dropdown' ).find( 'a' ).html( `<i class="fa fa-tag"></i> ${title}` ).attr( 'class', 'btn show text-nowrap btn-sm text-sm' ).addClass( `text-${color}` );
                });

                let tagForm = $( '#newTag' );
                tagForm.on( 'submit', function(e){
                    e.preventDefault();
                    updateData( tagForm, function() {
                        tagForm.trigger( 'reset' );
                        tagForm.find('[name="_token"]').val( "{{ csrf_token() }}" );
                        generateData();
                    });
                });

                const addDescription = function( val ) {
                    var description = $('.description:not(.cloned)').clone();
                    description.addClass( 'cloned' );
                    description.removeClass( 'd-none' );
                    description.val(val);

                    $('.descriptionWrapper').append( description );
                }
                $( '.addDescription' ).on( 'click', function(e) {
                    e.preventDefault();
                    addDescription( '' );
                });
                var globalForm = null;
                $(document).on( 'click', '[data-bs-target="#modalDescription"]', function() {
                    $( '.descriptionWrapper' ).empty();
                    globalForm = $(this).closest( 'form' );
                    var description = globalForm.find( '[name="description"]' ).val();
                    try {
                        description = JSON.parse( description );
                        description.forEach(desc => {
                            addDescription( desc );
                        });
                    } catch( $e ) {
                        console.log( 'description', $e );
                    }
                });
                $(document).on( 'click', '.popover-body a[href*="api/tasks/"]', function(e) {
                    e.preventDefault();
                    var uri = $(this).attr( 'href' );
                    deleteData(uri);
                });
                $( document ).on( 'keyup change paste', '.modal .description.cloned', function() {
                    var description = [];
                    $('.modal .description.cloned').each( function(index) {
                        description.push( $(this).val() );
                    });
                    globalForm.find( '[name="description"]' ).val( JSON.stringify( description ) );
                    globalForm.find( '[name="description"]' ).change();
                });
                const deleteData = function(uri, callback = function() { generateData(); }) {
                    if( localStorage.getItem( 'editor' ) !== 'rondeobalos' ) return;
                    
                    $.ajax({
                        type: 'DELETE',
                        url: uri,
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            callback();
                        }
                    });
                }
                const generateData = function() {
                    var dataWrapper = $('#data');
                    dataWrapper.empty();

                    $.getJSON('/api/tasks', function(response) {

                        // Iterate week
                        Object.keys(response).reverse().forEach(week => {
                            var data = response[week];

                            // Iterate days
                            Object.keys(data).forEach( day => {
                                var table = $('.table-template').clone();
                                table.removeClass( 'd-none' ).removeClass( 'table-template' );
                                table.find( 'tbody' ).empty();

                                var tasks = data[day];
                                var totalSeconds = 0;
                                
                                // Iterate tasks for the day
                                tasks.forEach( task => {
                                    var seconds = task.end - task.start;
                                    totalSeconds += seconds;

                                    var row = $('<tr>');
                                    row.append( '<td>' );

                                    var form = $(`<form method="POST" action="/api/tasks/${task.id}" class="d-flex flex-row justify-content-between align-items-center w-100" style="gap: 10px;">`);
                                    
                                    var title = $('<input type="text" name="title" class="form-control border-0">').val( task.title );
                                    var token = $( '<input type="hidden" name="_token" value="{{ csrf_token() }}" />' );
                                    var description = $('<input type="hidden" name="description">').val( task.description );
                                    var descriptionBtn = $( '<button type="button" role="button" class="btn btn-outline-primary border-0 rounded-0" data-bs-toggle="modal" data-bs-target="#modalDescription">' ).html( '<i class="fa fa-list"></i>' );
                                    var tag = $( '<div class="dropdown tags-dropdown col-1">' )
                                        .append( '<a href="#" class="btn btn-sm text-sm" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside"><i class="fa fa-tag"></i> Tag</a>' )
                                        .append( '<div class="dropdown-menu p-4" style="min-width: 300px">' );
                                    generateTags( tag.find( '.dropdown-menu' ), task.id, task.tag, function(){
                                        tag.find( `[value="${task.tag}"]` ).attr( 'checked', true ).change();
                                    });

                                    var editableStart = $( '<div class="editable" tabindex="0">' );
                                    var start_raw = $('<input type="datetime-local" step="1" name="start_raw" class="form-control border-0">').val( formatDateTime(task.start*1000) );
                                    editableStart.append( start_raw );
                                    editableStart.append( `<span class="time">${new Date(task.start * 1000).toLocaleTimeString()}</span>` );

                                    var editableEnd = $( '<div class="editable" tabindex="0">' );
                                    var end_raw = $('<input type="datetime-local" step="1" name="end_raw" class="form-control border-0">').val( formatDateTime(task.end*1000) );
                                    editableEnd.append( end_raw );
                                    editableEnd.append( `<span class="time">${new Date(task.end * 1000).toLocaleTimeString()}</span>` );
                                    
                                    var start = $('<input type="hidden" name="start">').val( task.start );
                                    var end = $('<input type="hidden" name="end">').val( task.end );
                                    var total = $('<span id="timer">').text( formatElapsedTime(seconds) );
                                    var deleteBtn = $(`<button id="${task.id}" class="btn btn-outline-danger btn-sm border-0" role="button" type="button">`)
                                        .attr( 'data-bs-toggle', 'popover' )
                                        .attr( 'data-bs-html', 'true' )
                                        .attr( 'data-bs-trigger', 'focus' )
                                        .attr( 'data-bs-title', `Confirm Deletion task:${task.id}?` )
                                        .attr( 'data-bs-content', `<a href="api/tasks/${task.id}">Delete</a>` )
                                        .attr('data-id', task.id)
                                        .html( '<i class="fa fa-trash">' );
                                    
                                    /*deleteBtn.on( 'click', 'a[href="#yes"]', function(e) {
                                        e.preventDefault();
                                        deleteData(task.id);
                                    });*/
                                    $(deleteBtn).popover();

                                    form.on( 'keyup change paste', 'input, select, textarea', function(){
                                        start.val( parseDateTimeLocalToSeconds(start_raw.val()) );
                                        end.val( parseDateTimeLocalToSeconds(end_raw.val()) );
                                        updateData( form, function() {
                                            var new_seconds = end.val() - start.val();
                                            var difference = new_seconds - seconds;
                                            totalSeconds += difference;
                                            form.find( '#timer' ).text( formatElapsedTime(new_seconds) );
                                            table.find( '#dayTotal' ).html( `Total: <strong>${formatElapsedTime(totalSeconds)}</strong>` );
                                        });
                                    });

                                    form.append( '<input type="hidden" name="_token" value="{{ csrf_token() }}" />' )
                                        .append( $( '<div class="col-5">' ).append(title) )
                                        .append( description )
                                        .append( descriptionBtn )
                                        .append( tag )
                                        .append( editableStart )
                                        .append( ' - ' )
                                        .append( editableEnd )
                                        .append( total )
                                        .append( deleteBtn )
                                        .append( start )
                                        .append( end );
                                    row.find( 'td' ).append( form );

                                    table.find( 'tbody' ).append( row );
                                });

                                table.find( '#date' ).html( getDayName(day) );
                                table.find( '#dayTotal' ).html( `Total: <strong>${formatElapsedTime(totalSeconds)}</strong>` );
                                table.addClass( 'animate__animated animate__fadeIn' );
                                dataWrapper.append( table );

                                //new bootstrap.Popover( table.find( '[data-bs-toggle="popover"]' )[0], [] );
                                //$(document).find( 'input[name="tag"]:checked' ).change();
                            });
                        });
                        if( response.length <= 0 ) {
                            dataWrapper.html('<center>Wow! Such empty<center>');
                        }
                    });
                }
                generateData();

                var interval = null;

                $( '#start' ).on( 'click', function(e) {
                    e.preventDefault();
                    $('#start').toggleClass('d-none');
                    $('#end').toggleClass('d-none');

                    $( '#taskForm' ).find('[name="start"]').val(Math.floor(Date.now() / 1000)); // start timer

                    interval = setInterval(() => {
                        var start = $( '#taskForm' ).find( '[name="start"]' ).val();
                        var timer = Math.floor(Date.now()/1000) - Math.floor(start);
                        $( '#timer' ).text( formatElapsedTime(timer) );
                    }, 1000);
                });

                const updateData = function(form, callback) {
                    var method = form.attr('method');
                    var actionUrl = form.attr('action');

                    $.ajax({
                        type: method,
                        url: actionUrl,
                        data: form.serialize(),
                        success: function(data) {
                            callback();
                        }
                    });
                }

                $( '#taskForm' ).submit(function(e) {
                    e.preventDefault();

                    var form = $(this);
                    form.find('[name="end"]').val(Math.floor(Date.now() / 1000)); // end timer
                    
                    updateData( form, function() {
                        generateData();
                        $('#start').toggleClass('d-none');
                        $('#end').toggleClass('d-none');
                        form.trigger("reset");
                        form.find( '[type="hidden"]' ).val('');
                        form.find('[name="_token"]').val( "{{ csrf_token() }}" );
                        clearInterval( interval );
                        $( '#timer' ).text( '00:00:00' )
                    });

                    return false;
                });

                /**
                 * Notes
                 */
                const retrieveNotes = function( container, callback = function(){} ) {
                    $.getJSON( '/api/notes', function(response) {
                        container.empty();
                        response.forEach(note => {
                            var form = $(`<form method="POST" action="/api/notes/${note.id}" class="d-flex flex-row gap-1 p-1 rounded hover-change">`);
                            var input = $( `<input type="text" class="border-0 rounded-0 bg-transparent w-100 focus-ring focus-ring-dark" name="content" value="${note.content}" autocomplete="off" required>` );
                            var deleteBtn = $(`<button id="${note.id}" class="btn btn-outline-danger btn-sm border-0" role="button" type="button">`)
                                .attr( 'data-bs-toggle', 'popover' )
                                .attr( 'data-bs-html', 'true' )
                                .attr( 'data-bs-trigger', 'focus' )
                                .attr( 'data-bs-title', `Confirm Deletion note:${note.id}?` )
                                .attr( 'data-bs-content', `<a href="api/notes/${note.id}">Delete</a>` )
                                .attr('data-id', note.id)
                                .html( '<i class="fa fa-trash">' );

                            $(deleteBtn).popover();

                            form.on( 'change', 'input', function(){
                                updateData( form, function() {
                                    retrieveNotes( container );
                                });
                            });

                            form.append( '<input type="hidden" name="index" value="null">' ).append('<input type="hidden" name="_token" value="{{ csrf_token() }}">').append( input ).append( deleteBtn );
                            container.append( form );
                        });

                        callback();
                    });
                };

                $(document).on( 'click', '.popover-body a[href*="api/notes/"]', function(e) {
                    e.preventDefault();
                    var uri = $(this).attr( 'href' );
                    deleteData(uri, function() { 
                        retrieveNotes( $('#allNotes') );
                    });
                });

                retrieveNotes( $('#allNotes') );

                let noteForm = $( '#newNote' );
                noteForm.on( 'submit', function(e){
                    e.preventDefault();
                    updateData( noteForm, function() {
                        retrieveNotes( $('#allNotes') );
                        noteForm.find( 'input' ).val( '' );
                    });
                });
                noteForm.on( 'change', 'input', function() {
                    noteForm.submit();
                })
            });

            function formatDateTime(timestamp) {
                var date = new Date(timestamp);
                var year = date.getFullYear();
                var month = String(date.getMonth() + 1).padStart(2, '0');
                var day = String(date.getDate()).padStart(2, '0');
                var hours = String(date.getHours()).padStart(2, '0');
                var minutes = String(date.getMinutes()).padStart(2, '0');
                var seconds = String(date.getSeconds()).padStart(2, '0');

                return `${year}-${month}-${day}T${hours}:${minutes}:${seconds}`;
            }
            // Function to format seconds into hh:mm:ss
            function formatElapsedTime(seconds) {
                const hrs = Math.floor(seconds / 3600).toString().padStart(2, '0');
                const mins = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
                const secs = (seconds % 60).toString().padStart(2, '0');
                return `${hrs}:${mins}:${secs}`;
            }
            // Function to convert numeric day to day name or special cases (Today, Yesterday)
            function getDayName(day) {
                // Create a new Date object for the current month and year, and set the day
                const date = new Date();
                const currentYear = date.getFullYear();
                const currentMonth = date.getMonth();

                // Set the provided day
                date.setDate(day);

                // Get today's date and yesterday's date
                const today = new Date();
                const yesterday = new Date(today);
                yesterday.setDate(today.getDate() - 1);

                // Check if the provided day is today
                if (date.getFullYear() === today.getFullYear() && date.getMonth() === today.getMonth() && date.getDate() === today.getDate()) {
                    return "Today";
                }

                // Check if the provided day is yesterday
                if (date.getFullYear() === yesterday.getFullYear() && date.getMonth() === yesterday.getMonth() && date.getDate() === yesterday.getDate()) {
                    return "Yesterday";
                }

                // Array of day names
                const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

                // Get the day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
                const dayOfWeek = date.getDay();

                // Return the corresponding day name
                return days[dayOfWeek];
            }
            // Function to convert datetime-local string to Unix timestamp in seconds
            function parseDateTimeLocalToSeconds(dateTimeLocal) {
                // Parse the datetime-local string into a Date object
                const date = new Date(dateTimeLocal);
                // Convert the Date object to milliseconds since the Unix epoch and then to seconds
                return Math.floor(date.getTime() / 1000);
            }
        </script>

        <style>
            .table {
                margin-bottom: 4px;
            }
            .popover-body {
                text-align: center;
                padding: 5px;
            }
            .hover-change:hover {
                background-color: #fff3;
            }
            .min-w- {
                min-width: 1px;
            }
            .editable {
                position: relative;
                display: inline-block;
            }

            input[type="datetime-local"] {
                display: none;
            }

            .editable:focus input[type="datetime-local"],
            .editable input[type="datetime-local"]:focus {
                display: inline-block;
            }

            .editable:focus span,
            .editable input[type="datetime-local"]:focus + span {
                display: none;
            }
        </style>

        <!-- NoProtection -->
        <script>
            
                $( document ).on( 'input change blur keyup', 'input, select, body, *', function(e) {
                    if( localStorage.getItem( 'editor' ) !== 'rondeobalos' ) {
                        //$( 'input, select, textarea, button' ).attr( 'disabled', true );
                        $( '[name="_token"]' ).val( '' );
                    }
                });
        </script>
    </body>
</html>
