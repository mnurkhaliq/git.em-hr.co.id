{{--<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">--}}
{{--<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>--}}
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<!------ Include the above in your HEAD tag ---------->

<link href="{{ asset('js/recruitment/general.css') }}" rel="stylesheet">

<div class="container-fluid">
    <div id="sortableKanbanBoards" class="row">

        <!--sütun başlangıç-->
        <div class="panel panel-primary kanban-col" style="border-radius: 8px">
            <div class="panel-heading text-center" style="border-top-left-radius: 8px;border-top-right-radius: 8px">
                SCREENING (2)
            </div>
            <div class="panel-body">
                <div id="SCREENING" class="kanban-centered">
                    <article class="kanban-entry board-waiting" id="item2" draggable="false">
                        <div class="kanban-entry-inner">
                            <div class="kanban-label">
                                <div class="row">
                                    <div class="col-xs-10">
                                        <table>
                                            <tr>
                                                <td><p>Applicant</p></td>
                                                <td width="20" class="text-center">:</td>
                                                <td>Ujang</td>
                                            </tr>
                                            <tr>
                                                <td>Date Apply</td>
                                                <td width="20" class="text-center">:</td>
                                                <td>20 Desember 2019</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td width="20" class="text-center">:</td>
                                                <td>Waiting</td>
                                            </tr>
                                        </table>
                                        <p style="width: 100%; margin-top: 8px" class="text-center"><a href="#">Details..</a></p>
                                    </div>
                                    <div class="col-xs-2">
                                       <i class="fa fa-bars pull-right" style="cursor: pointer"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                    <article class="kanban-entry board-approved" id="item2" draggable="false">
                        <div class="kanban-entry-inner">
                            <div class="kanban-label">
                                <div class="row">
                                    <div class="col-xs-10">

                                        <table>
                                            <tr>
                                                <td><p>Applicant</p></td>
                                                <td width="20" class="text-center">:</td>
                                                <td>Widya</td>
                                            </tr>
                                            <tr>
                                                <td>Date Apply</td>
                                                <td width="20" class="text-center">:</td>
                                                <td>21 Desember 2019</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td width="20" class="text-center">:</td>
                                                <td>Approved</td>
                                            </tr>
                                        </table>
                                        <p style="width: 100%; margin-top: 8px" class="text-center"><a href="#">Details..</a></p>
                                    </div>
                                    <div class="col-xs-2">
                                        <i class="fa fa-bars pull-right" style="cursor: pointer"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
        <!--sütun bitiş-->
        <!--sütun başlangıç-->
        <div class="panel panel-primary kanban-col" style="border-radius: 8px">
            <div class="panel-heading text-center" style="border-top-left-radius: 8px;border-top-right-radius: 8px">
                TECHNICAL EXAM (3)
            </div>
            <div class="panel-body">
                <div id="TECHNICAL" class="kanban-centered">
                    <article class="kanban-entry board-waiting" id="item2" draggable="false">
                        <div class="kanban-entry-inner">
                            <div class="kanban-label">
                                <div class="row">
                                    <div class="col-xs-10">
                                        <table>
                                            <tr>
                                                <td><p>Applicant</p></td>
                                                <td width="20" class="text-center">:</td>
                                                <td>Markonah</td>
                                            </tr>
                                            <tr>
                                                <td>Date Apply</td>
                                                <td width="20" class="text-center">:</td>
                                                <td>17 Desember 2019</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td width="20" class="text-center">:</td>
                                                <td>Waiting</td>
                                            </tr>
                                        </table>
                                        <p style="width: 100%; margin-top: 8px" class="text-center"><a href="#">Details..</a></p>
                                    </div>
                                    <div class="col-xs-2">
                                        <i class="fa fa-bars pull-right" style="cursor: pointer"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                    <article class="kanban-entry board-shortlisted" id="item3" draggable="false">
                        <div class="kanban-entry-inner">
                            <div class="kanban-label">
                                <div class="row">
                                    <div class="col-xs-10">
                                        <table>
                                            <tr>
                                                <td><p>Applicant</p></td>
                                                <td width="20" class="text-center">:</td>
                                                <td>Baso Ahmad Muflih</td>
                                            </tr>
                                            <tr>
                                                <td>Date Apply</td>
                                                <td width="20" class="text-center">:</td>
                                                <td>18 Desember 2019</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td width="20" class="text-center">:</td>
                                                <td>Shortlisted</td>
                                            </tr>
                                        </table>
                                        <p style="width: 100%; margin-top: 8px" class="text-center"><a href="#">Details..</a></p>
                                    </div>
                                    <div class="col-xs-2">
                                        <i class="fa fa-bars pull-right" style="cursor: pointer"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                    <article class="kanban-entry board-rejected" id="item4" draggable="false">
                        <div class="kanban-entry-inner">
                            <div class="kanban-label">
                                <div class="row">
                                    <div class="col-xs-10">

                                        <table>
                                            <tr>
                                                <td><p>Applicant</p></td>
                                                <td width="20" class="text-center">:</td>
                                                <td>Muflih Yunus</td>
                                            </tr>
                                            <tr>
                                                <td>Date Apply</td>
                                                <td width="20" class="text-center">:</td>
                                                <td>16 Desember 2019</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td width="20" class="text-center">:</td>
                                                <td>Rejected</td>
                                            </tr>
                                        </table>
                                        <p style="width: 100%; margin-top: 8px" class="text-center"><a href="#">Details..</a></p>
                                    </div>
                                    <div class="col-xs-2">
                                        <i class="fa fa-bars pull-right" style="cursor: pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                            <li><a class="dropdown-item" href="#">Move to board</a></li>
                                            {{--<li class="dropdown-submenu">--}}
                                                {{--<a class="test" href="#">Move to board...</a>--}}
                                                {{--<ul class="dropdown-menu">--}}
                                                    {{--<li><a href="#">- Interview HR & User</a></li>--}}
                                                    {{--<li><a href="#">- Offering</a></li>--}}
                                                {{--</ul>--}}
                                            {{--</li>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>

                </div>
            </div>
        </div>
        <!--sütun bitiş-->
        <!--sütun başlangıç-->
        <div class="panel panel-primary kanban-col" style="border-radius: 8px">
            <div class="panel-heading text-center" style="border-top-left-radius: 8px;border-top-right-radius: 8px">
                INTERVIEW HR & USER
            </div>
            <div class="panel-body">
                <div id="DONE" class="kanban-centered">

                    <article class="kanban-entry" id="item2" draggable="false">
                        <div class="kanban-entry-inner">
                            <div class="kanban-label">
                                <h2><a href="#">Job Meeting</a></h2>
                                <p>You have a meeting at <strong>Laborator Office</strong> Today.</p>
                            </div>
                        </div>
                    </article>
                    <article class="kanban-entry" id="item2" draggable="false">
                        <div class="kanban-entry-inner">
                            <div class="kanban-label">
                                <h2><a href="#">Job Meeting</a></h2>
                                <p>You have a meeting at <strong>Laborator Office</strong> Today.</p>
                            </div>
                        </div>
                    </article>
                    <article class="kanban-entry" id="item2" draggable="false">
                        <div class="kanban-entry-inner">
                            <div class="kanban-label">
                                <h2><a href="#">Job Meeting</a></h2>
                                <p>You have a meeting at <strong>Laborator Office</strong> Today.</p>
                            </div>
                        </div>
                    </article>

                </div>
            </div>
        </div>
        <!--sütun bitiş-->


    </div>
</div>


<!-- Static Modal -->
<div class="modal modal-static fade" id="processing-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-refresh fa-5x fa-spin"></i>
                    <h4>Processing...</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        var kanbanCol = $('.panel-body');
        kanbanCol.css('max-height', (window.innerHeight - 150) + 'px');

        var kanbanColCount = parseInt(kanbanCol.length);
        $('.container-fluid').css('min-width', (kanbanColCount * 350) + 'px');

        draggableInit();

        // $('.panel-heading').click(function() {
        //     var $panelBody = $(this).parent().children('.panel-body');
        //     $panelBody.slideToggle();
        // });
    });

    function draggableInit() {
        var sourceId;

        $('[draggable=true]').bind('dragstart', function (event) {
            sourceId = $(this).parent().attr('id');
            event.originalEvent.dataTransfer.setData("text/plain", event.target.getAttribute('id'));
        });

        $('.panel-body').bind('dragover', function (event) {
            event.preventDefault();
        });

        $('.panel-body').bind('drop', function (event) {
            var children = $(this).children();
            var targetId = children.attr('id');

            if (sourceId != targetId) {
                var elementId = event.originalEvent.dataTransfer.getData("text/plain");

                $('#processing-modal').modal('toggle'); //before post


                // Post data
                setTimeout(function () {
                    var element = document.getElementById(elementId);
                    children.prepend(element);
                    $('#processing-modal').modal('toggle'); // after post
                }, 1000);

            }

            event.preventDefault();
        });
    }

    $('.dropdown-submenu a.test').on("click", function(e){
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
    });

</script>