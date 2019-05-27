<?php

// With Authorization (Login is required)

Route::group(array('module' => 'BoardMeting', 'middleware' => ['auth','checkAdmin'], 'namespace' => 'App\Modules\BoardMeting\Controllers'), function() {
    /**/


    /* Board meting related */
    Route::get('/board-meting/lists', "BoardMetingController@lists");
    Route::get('/board-meting/generate-docx', "BoardMetingController@generateDocx");
    Route::post('board-meting/get-row-details-data', "BoardMetingController@getRowDetailsData");
    Route::post('board-meting/store-meeting', "BoardMetingController@storeMeeting");
    Route::post('board-meting/update-meeting', "BoardMetingController@updateMeeting");
    Route::get('board-meting/new-board-meting', "BoardMetingController@newBoardMeting");
    Route::get('board-meting/edit/{id}', "BoardMetingController@editBM");
    Route::get('board-meting/check-number', "BoardMetingController@checkNumber");
    Route::get('board-meting/create-share-document', "BoardMetingController@createShareDocument");
    Route::get('board-meting/view-share-document/{id}', "BoardMetingController@viewShareDocument");
    Route::get('board-meting/view-news/{id}', "BoardMetingController@viewNews");
    Route::post('board-meting/share-document/store-document', "BoardMetingController@storeShareDocument");
    Route::post('board-meting/share-document/get-share-document-data', "BoardMetingController@getShareDocument");
    Route::post('board-meting/complete-meeting/publish', "BoardMetingController@completeMeetingPublish");



    /* Fixed board meeting*/
    Route::get('board-meting/fixed-meeting', "BoardMetingController@fixedMeeting");
    Route::get('board-meting/complete-meeting', "BoardMetingController@completeMeeting");
    Route::post('board-meting/get-complete-row-details-data', "BoardMetingController@getCompleteMeeting");


    /* committee related */
    Route::get('board-meting/committee/{board_meeting_id}', "CommitteeController@index");
    Route::post('board-meting/committee/store', "CommitteeController@storeCommittee");
    Route::post('board-meting/committee/get-data', "CommitteeController@getData");
    Route::get('board-meting/committee/member-edit/{member_id}', "CommitteeController@memberEdit");
    Route::post('board-meting/committee/member-edit', "CommitteeController@updateMember");
    Route::post('/board-meting/committee/deleteMember', "CommitteeController@deleteMember");
    Route::post('/board-meting/committee/getUserInfo', "CommitteeController@getUserInfo");
    Route::post('board-meting/committee/check-chairperson-type', "CommitteeController@checkChairpersonType");
    Route::get('board-meting/committee/chairman/{board_meeting_id}', "CommitteeController@chairmanChoice");
    Route::post('board-meting/committee/get-data-for-chairman', "CommitteeController@getDataForChairmanChoice");
    Route::post('board-meting/committee/save-chairperson-choice', "CommitteeController@saveChairpersonChoice");
    Route::get('board-meting/committee/notice-generate/{board_meeting_id}', "CommitteeController@noticeGenerate");
    Route::get('board-meting/committee/notice/view/{board_meeting_id}', "CommitteeController@notice");
    Route::get('board-meting/committee/notice/{board_meeting_id}', "CommitteeController@noticeGenerate");
    Route::get('board-meting/committee/notice-publish/{board_meeting_id}', "CommitteeController@noticePublish");
    Route::get('board-meting/committee/notice-publish/{board_meeting_id}/{r}', "CommitteeController@noticeRePublish");
    Route::post('board-meting/committee/user_list', "CommitteeController@getCommitteeList");


    /* Agenda related */
    Route::get('/board-meting/agenda/create-new-agenda/{board_meting_id}', "AgendaController@createNewAgenda");
    Route::get('/board-meting/agenda/download/{board_meting_id}', "AgendaController@downloadAgenda");
    Route::get('/board-meting/agenda/doc-download/{board_meting_id}', "AgendaController@downloadAgendaAsDoc");
    Route::get('/board-meting/agenda/minutes/download/{board_meting_id}', "AgendaController@downloadAgendaMinutes");
    Route::get('/board-meting/agenda/minutes/doc-download/{board_meting_id}', "AgendaController@generateMeetingMinutesDoc");
    Route::post('/board-meting/agenda/deleteAgenda', "AgendaController@deleteAgenda");
    Route::get('board-meting/boardMeting/{status?}/{desk?}',[
        'as' => 'board-meting.agendaWiseBoardMeting',
        'uses' => 'AgendaController@agendaWiseBoardMeting'
    ]);
    Route::get('/board-meting/get-list/{status?}/{desk?}',[
        'as' => 'board-meting.agendaWiseProcessList',
        'uses' => 'AgendaController@agendaWiseProcessList'
    ]);

    Route::post('/board-meting/save-board-meting', "AgendaController@saveAgendaWiseBoardMeting");
    Route::get('/board-meting/agenda/list/{id}', "AgendaController@view");
    Route::get('/board-meting/agenda/deleteItem', "AgendaController@deleteItem");
    Route::post('/board-meting/agenda/store-agenda', "AgendaController@storeAgenda");
    Route::post('/board-meting/agenda/process', "AgendaController@updateProcess");
    Route::post('/board-meting/agenda/update-remarks', "AgendaController@updateRemarks");


    /* Agenda Process */
    Route::get('/board-meting/agenda/get-roll-over-date', "AgendaController@getRollOverDate");
    Route::get('/board-meting/agenda/process/{id}', "AgendaController@agendaWiseProcess");
    Route::post('/board-meting/process/delete-board-meeting-process', "AgendaController@deleteBoardMeetingProcess");
    Route::post('/board-meting/agenda/process/save-individual-action', "AgendaController@saveIndividualAction");


    Route::get('/board-meting/agenda/edit/{id}', "AgendaController@editAgenda");
    Route::patch('/board-meting/agenda/update-agenda/{id}', "AgendaController@updateAgenda");
    Route::post('board-meting/agenda/get-agenda-data', "AgendaController@getAgendaData");
    Route::post('board-meting/agenda-process-remarks', "AgendaController@getAgendaProcessRemarks");
    Route::post('board-meting/agenda-remarks', "AgendaController@getAgendaRemarks");
    Route::get('board-meting/pdf', "AgendaController@pdfview");

});