<?php
$arUrlRewrite=array (
  585 => 
  array (
    'CONDITION' => '#^/pub/annuals/([0-9a-zA-Z~]+)/#',
    'RULE' => 'shortCode=$1',
    'ID' => NULL,
    'PATH' => '/pub/annual_summary.php',
    'SORT' => 50,
  ),
  586 => 
  array (
    'CONDITION' => '#^/pub/annual_summary/([0-9a-z\\.]+)/([0-9a-z\\.\\_]+)/\\?user_lang=[a-z]+#',
    'RULE' => 'signedId=$1&signedType=$2',
    'ID' => NULL,
    'PATH' => '/pub/annual_summary.php',
    'SORT' => 60,
  ),
  140 => 
  array (
    'CONDITION' => '#^/site_qn/pub/document/([0-9a-zA-Z_-]+)/([0-9a-zA-Z]+)/([^/]*)#',
    'RULE' => 'id=$1&hash=$2',
    'ID' => 'bitrix:documentgenerator.view',
    'PATH' => '/site_qn/pub/document.php',
    'SORT' => 100,
  ),
  434 => 
  array (
    'CONDITION' => '#^/site_pq/pub/document/([0-9a-zA-Z_-]+)/([0-9a-zA-Z]+)/([^/]*)#',
    'RULE' => 'id=$1&hash=$2',
    'ID' => 'bitrix:documentgenerator.view',
    'PATH' => '/site_pq/pub/document.php',
    'SORT' => 100,
  ),
  238 => 
  array (
    'CONDITION' => '#^/site_gm/pub/document/([0-9a-zA-Z_-]+)/([0-9a-zA-Z]+)/([^/]*)#',
    'RULE' => 'id=$1&hash=$2',
    'ID' => 'bitrix:documentgenerator.view',
    'PATH' => '/site_gm/pub/document.php',
    'SORT' => 100,
  ),
  336 => 
  array (
    'CONDITION' => '#^/site_rj/pub/document/([0-9a-zA-Z_-]+)/([0-9a-zA-Z]+)/([^/]*)#',
    'RULE' => 'id=$1&hash=$2',
    'ID' => 'bitrix:documentgenerator.view',
    'PATH' => '/site_rj/pub/document.php',
    'SORT' => 100,
  ),
  266 => 
  array (
    'CONDITION' => '#^/site_gm/pub/calendar-event/([0-9]+)/([0-9a-zA-Z]+)/?([^/]*)#',
    'RULE' => 'event_id=$1&hash=$2',
    'ID' => 'bitrix:calendar.pub.event',
    'PATH' => '/site_gm/pub/calendar_event.php',
    'SORT' => 100,
  ),
  462 => 
  array (
    'CONDITION' => '#^/site_pq/pub/calendar-event/([0-9]+)/([0-9a-zA-Z]+)/?([^/]*)#',
    'RULE' => 'event_id=$1&hash=$2',
    'ID' => 'bitrix:calendar.pub.event',
    'PATH' => '/site_pq/pub/calendar_event.php',
    'SORT' => 100,
  ),
  364 => 
  array (
    'CONDITION' => '#^/site_rj/pub/calendar-event/([0-9]+)/([0-9a-zA-Z]+)/?([^/]*)#',
    'RULE' => 'event_id=$1&hash=$2',
    'ID' => 'bitrix:calendar.pub.event',
    'PATH' => '/site_rj/pub/calendar_event.php',
    'SORT' => 100,
  ),
  168 => 
  array (
    'CONDITION' => '#^/site_qn/pub/calendar-event/([0-9]+)/([0-9a-zA-Z]+)/?([^/]*)#',
    'RULE' => 'event_id=$1&hash=$2',
    'ID' => 'bitrix:calendar.pub.event',
    'PATH' => '/site_qn/pub/calendar_event.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/docs/pub/(?<hash>[0-9a-f]{32})/(?<action>[0-9a-zA-Z]+)/\\?#',
    'RULE' => 'hash=$1&action=$2&',
    'ID' => 'bitrix:disk.external.link',
    'PATH' => '/docs/pub/index.php',
    'SORT' => 100,
  ),
  48 => 
  array (
    'CONDITION' => '#^/pub/document/([0-9a-zA-Z_-]+)/([0-9a-zA-Z]+)/([^/]*)#',
    'RULE' => 'id=$1&hash=$2',
    'ID' => 'bitrix:documentgenerator.view',
    'PATH' => '/pub/document.php',
    'SORT' => 100,
  ),
  514 => 
  array (
    'CONDITION' => '#^/site_gm/pub/calendar-sharing/([0-9a-zA-Z]+)/?([^/]*)#',
    'RULE' => 'hash=$1',
    'ID' => 'bitrix:calendar.pub.sharing',
    'PATH' => '/site_gm/pub/calendar_sharing.php',
    'SORT' => 100,
  ),
  515 => 
  array (
    'CONDITION' => '#^/site_pq/pub/calendar-sharing/([0-9a-zA-Z]+)/?([^/]*)#',
    'RULE' => 'hash=$1',
    'ID' => 'bitrix:calendar.pub.sharing',
    'PATH' => '/site_pq/pub/calendar_sharing.php',
    'SORT' => 100,
  ),
  516 => 
  array (
    'CONDITION' => '#^/site_rj/pub/calendar-sharing/([0-9a-zA-Z]+)/?([^/]*)#',
    'RULE' => 'hash=$1',
    'ID' => 'bitrix:calendar.pub.sharing',
    'PATH' => '/site_rj/pub/calendar_sharing.php',
    'SORT' => 100,
  ),
  71 => 
  array (
    'CONDITION' => '#^/pub/calendar-event/([0-9]+)/([0-9a-zA-Z]+)/?([^/]*)#',
    'RULE' => 'event_id=$1&hash=$2',
    'ID' => 'bitrix:calendar.pub.event',
    'PATH' => '/pub/calendar_event.php',
    'SORT' => 100,
  ),
  580 => 
  array (
    'CONDITION' => '#^/disk/file/(?<unique_code>[0-9a-zA-Z]{20})/edit(\\/?)#',
    'RULE' => 'action=disk.api.unifiedlinkcontroller.edit&uniqueCode=$1&',
    'ID' => NULL,
    'PATH' => '/bitrix/services/main/ajax.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/disk/(?<action>[0-9a-zA-Z]+)/(?<fileId>[0-9]+)/\\?#',
    'RULE' => 'action=$1&fileId=$2&',
    'ID' => 'bitrix:disk.services',
    'PATH' => '/bitrix/services/disk/index.php',
    'SORT' => 100,
  ),
  139 => 
  array (
    'CONDITION' => '#^/site_qn/pub/form/([0-9a-z_]+?)/([0-9a-z]+?)/.*#',
    'RULE' => 'form_code=$1&sec=$2',
    'ID' => '',
    'PATH' => '/site_qn/pub/form.php',
    'SORT' => 100,
  ),
  237 => 
  array (
    'CONDITION' => '#^/site_gm/pub/form/([0-9a-z_]+?)/([0-9a-z]+?)/.*#',
    'RULE' => 'form_code=$1&sec=$2',
    'ID' => '',
    'PATH' => '/site_gm/pub/form.php',
    'SORT' => 100,
  ),
  433 => 
  array (
    'CONDITION' => '#^/site_pq/pub/form/([0-9a-z_]+?)/([0-9a-z]+?)/.*#',
    'RULE' => 'form_code=$1&sec=$2',
    'ID' => '',
    'PATH' => '/site_pq/pub/form.php',
    'SORT' => 100,
  ),
  335 => 
  array (
    'CONDITION' => '#^/site_rj/pub/form/([0-9a-z_]+?)/([0-9a-z]+?)/.*#',
    'RULE' => 'form_code=$1&sec=$2',
    'ID' => '',
    'PATH' => '/site_rj/pub/form.php',
    'SORT' => 100,
  ),
  581 => 
  array (
    'CONDITION' => '#^/disk/file/(?<unique_code>[0-9a-zA-Z]{20})(\\/?)#',
    'RULE' => 'action=disk.api.unifiedlinkcontroller.view&uniqueCode=$1&',
    'ID' => NULL,
    'PATH' => '/bitrix/services/main/ajax.php',
    'SORT' => 100,
  ),
  216 => 
  array (
    'CONDITION' => '#^/site_qn/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1',
    'ID' => '',
    'PATH' => '/site_qn/desktop_app/router.php',
    'SORT' => 100,
  ),
  314 => 
  array (
    'CONDITION' => '#^/site_gm/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1',
    'ID' => '',
    'PATH' => '/site_gm/desktop_app/router.php',
    'SORT' => 100,
  ),
  412 => 
  array (
    'CONDITION' => '#^/site_rj/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1',
    'ID' => '',
    'PATH' => '/site_rj/desktop_app/router.php',
    'SORT' => 100,
  ),
  510 => 
  array (
    'CONDITION' => '#^/site_pq/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1',
    'ID' => '',
    'PATH' => '/site_pq/desktop_app/router.php',
    'SORT' => 100,
  ),
  513 => 
  array (
    'CONDITION' => '#^/pub/calendar-sharing/([0-9a-zA-Z]+)/?([^/]*)#',
    'RULE' => 'hash=$1',
    'ID' => 'bitrix:calendar.pub.sharing',
    'PATH' => '/pub/calendar_sharing.php',
    'SORT' => 100,
  ),
  591 => 
  array (
    'CONDITION' => '#^/extranet/video/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1&videoconf',
    'ID' => 'call:conference',
    'PATH' => '/conference/videoconf.php',
    'SORT' => 100,
  ),
  164 => 
  array (
    'CONDITION' => '#^/site_qn/video/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1&videoconf',
    'ID' => NULL,
    'PATH' => '/site_qn/desktop_app/router.php',
    'SORT' => 100,
  ),
  360 => 
  array (
    'CONDITION' => '#^/site_rj/video/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1&videoconf',
    'ID' => NULL,
    'PATH' => '/site_rj/desktop_app/router.php',
    'SORT' => 100,
  ),
  262 => 
  array (
    'CONDITION' => '#^/site_gm/video/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1&videoconf',
    'ID' => NULL,
    'PATH' => '/site_gm/desktop_app/router.php',
    'SORT' => 100,
  ),
  458 => 
  array (
    'CONDITION' => '#^/site_pq/video/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1&videoconf',
    'ID' => NULL,
    'PATH' => '/site_pq/desktop_app/router.php',
    'SORT' => 100,
  ),
  127 => 
  array (
    'CONDITION' => '#^/site_qn/tasks/getfile/(\\d+)/(\\d+)/([^/]+)#',
    'RULE' => 'taskid=$1&fileid=$2&filename=$3',
    'ID' => NULL,
    'PATH' => '/site_qn/tasks/getfile.php',
    'SORT' => 100,
  ),
  323 => 
  array (
    'CONDITION' => '#^/site_rj/tasks/getfile/(\\d+)/(\\d+)/([^/]+)#',
    'RULE' => 'taskid=$1&fileid=$2&filename=$3',
    'ID' => NULL,
    'PATH' => '/site_rj/tasks/getfile.php',
    'SORT' => 100,
  ),
  421 => 
  array (
    'CONDITION' => '#^/site_pq/tasks/getfile/(\\d+)/(\\d+)/([^/]+)#',
    'RULE' => 'taskid=$1&fileid=$2&filename=$3',
    'ID' => NULL,
    'PATH' => '/site_pq/tasks/getfile.php',
    'SORT' => 100,
  ),
  225 => 
  array (
    'CONDITION' => '#^/site_gm/tasks/getfile/(\\d+)/(\\d+)/([^/]+)#',
    'RULE' => 'taskid=$1&fileid=$2&filename=$3',
    'ID' => NULL,
    'PATH' => '/site_gm/tasks/getfile.php',
    'SORT' => 100,
  ),
  18 => 
  array (
    'CONDITION' => '#^\\/?\\/mobile/web_mobile_component\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobile/webcomponent.php',
    'SORT' => 100,
  ),
  573 => 
  array (
    'CONDITION' => '#^/disk/boards/([0-9]+)/openAttachedDocument#',
    'RULE' => 'action=disk.integration.flipchart.openAttachedDocument&attachedObjectId=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/main/ajax.php',
    'SORT' => 100,
  ),
  400 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/document_numerators/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.document_numerators.list',
    'PATH' => '/site_rj/crm/configs/document_numerators/index.php',
    'SORT' => 100,
  ),
  498 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/document_numerators/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.document_numerators.list',
    'PATH' => '/site_pq/crm/configs/document_numerators/index.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/pub/pay/([\\w\\W]+)/([0-9a-zA-Z]+)/([^/]*)#',
    'RULE' => 'account_number=$1&hash=$2',
    'ID' => NULL,
    'PATH' => '/pub/payment.php',
    'SORT' => 100,
  ),
  204 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/document_numerators/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.document_numerators.list',
    'PATH' => '/site_qn/crm/configs/document_numerators/index.php',
    'SORT' => 100,
  ),
  302 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/document_numerators/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.document_numerators.list',
    'PATH' => '/site_gm/crm/configs/document_numerators/index.php',
    'SORT' => 100,
  ),
  563 => 
  array (
    'CONDITION' => '#^/pub/booking/confirmation/([0-9a-z\\.]+)/#',
    'RULE' => 'hash=$1',
    'ID' => 'bitrix:booking.pub.confirm',
    'PATH' => '/pub/booking/confirmation.php',
    'SORT' => 100,
  ),
  47 => 
  array (
    'CONDITION' => '#^/pub/form/([0-9a-z_]+?)/([0-9a-z]+?)/.*#',
    'RULE' => 'form_code=$1&sec=$2',
    'ID' => '',
    'PATH' => '/pub/form.php',
    'SORT' => 100,
  ),
  15 => 
  array (
    'CONDITION' => '#^\\/?\\/mobile/mobile_component\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobile/jscomponent.php',
    'SORT' => 100,
  ),
  436 => 
  array (
    'CONDITION' => '#^/site_pq/settings/configs/userconsent/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_pq/configs/userconsent.php',
    'SORT' => 100,
  ),
  240 => 
  array (
    'CONDITION' => '#^/site_gm/settings/configs/userconsent/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_gm/configs/userconsent.php',
    'SORT' => 100,
  ),
  142 => 
  array (
    'CONDITION' => '#^/site_qn/settings/configs/userconsent/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_qn/configs/userconsent.php',
    'SORT' => 100,
  ),
  338 => 
  array (
    'CONDITION' => '#^/site_rj/settings/configs/userconsent/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_rj/configs/userconsent.php',
    'SORT' => 100,
  ),
  11 => 
  array (
    'CONDITION' => '#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1',
    'ID' => NULL,
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  19 => 
  array (
    'CONDITION' => '#^/mobile/disk/(?<hash>[0-9]+)/download#',
    'RULE' => 'download=1&objectId=$1',
    'ID' => 'bitrix:mobile.disk.file.detail',
    'PATH' => '/mobile/disk/index.php',
    'SORT' => 100,
  ),
  529 => 
  array (
    'CONDITION' => '#^/site_gm/pub/payment-slip/([\\w\\W]+)/#',
    'RULE' => 'signed_payment_id=$1',
    'ID' => 'bitrix:salescenter.pub.payment.slip',
    'PATH' => '/site_gm/pub/payment_slip.php',
    'SORT' => 100,
  ),
  530 => 
  array (
    'CONDITION' => '#^/site_pq/pub/payment-slip/([\\w\\W]+)/#',
    'RULE' => 'signed_payment_id=$1',
    'ID' => 'bitrix:salescenter.pub.payment.slip',
    'PATH' => '/site_pq/pub/payment_slip.php',
    'SORT' => 100,
  ),
  531 => 
  array (
    'CONDITION' => '#^/site_rj/pub/payment-slip/([\\w\\W]+)/#',
    'RULE' => 'signed_payment_id=$1',
    'ID' => 'bitrix:salescenter.pub.payment.slip',
    'PATH' => '/site_rj/pub/payment_slip.php',
    'SORT' => 100,
  ),
  590 => 
  array (
    'CONDITION' => '#^/video/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1&videoconf',
    'ID' => 'call:conference',
    'PATH' => '/conference/videoconf.php',
    'SORT' => 100,
  ),
  300 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/deal_category/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.deal_category',
    'PATH' => '/site_gm/crm/configs/deal_category/index.php',
    'SORT' => 100,
  ),
  134 => 
  array (
    'CONDITION' => '#^/site_qn/shop/settings/permissions/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.config.permissions',
    'PATH' => '/site_qn/shop/settings/permissions/index.php',
    'SORT' => 100,
  ),
  202 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/deal_category/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.deal_category',
    'PATH' => '/site_qn/crm/configs/deal_category/index.php',
    'SORT' => 100,
  ),
  398 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/deal_category/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.deal_category',
    'PATH' => '/site_rj/crm/configs/deal_category/index.php',
    'SORT' => 100,
  ),
  441 => 
  array (
    'CONDITION' => '#^/site_pq/marketplace/configuration/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.configuration',
    'PATH' => '/site_pq/marketplace/configuration/index.php',
    'SORT' => 100,
  ),
  343 => 
  array (
    'CONDITION' => '#^/site_rj/marketplace/configuration/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.configuration',
    'PATH' => '/site_rj/marketplace/configuration/index.php',
    'SORT' => 100,
  ),
  147 => 
  array (
    'CONDITION' => '#^/site_qn/marketplace/configuration/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.configuration',
    'PATH' => '/site_qn/marketplace/configuration/index.php',
    'SORT' => 100,
  ),
  10 => 
  array (
    'CONDITION' => '#^/video([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1&videoconf',
    'ID' => NULL,
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  428 => 
  array (
    'CONDITION' => '#^/site_pq/shop/settings/permissions/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.config.permissions',
    'PATH' => '/site_pq/shop/settings/permissions/index.php',
    'SORT' => 100,
  ),
  496 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/deal_category/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.deal_category',
    'PATH' => '/site_pq/crm/configs/deal_category/index.php',
    'SORT' => 100,
  ),
  245 => 
  array (
    'CONDITION' => '#^/site_gm/marketplace/configuration/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.configuration',
    'PATH' => '/site_gm/marketplace/configuration/index.php',
    'SORT' => 100,
  ),
  232 => 
  array (
    'CONDITION' => '#^/site_gm/shop/settings/permissions/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.config.permissions',
    'PATH' => '/site_gm/shop/settings/permissions/index.php',
    'SORT' => 100,
  ),
  330 => 
  array (
    'CONDITION' => '#^/site_rj/shop/settings/permissions/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.config.permissions',
    'PATH' => '/site_rj/shop/settings/permissions/index.php',
    'SORT' => 100,
  ),
  565 => 
  array (
    'CONDITION' => '#^/extranet/vote-result/([0-9a-z\\.]+)#',
    'RULE' => 'signedAttachId=$1',
    'ID' => 'bitrix:voting.attached.result',
    'PATH' => '/extranet/vote-result/index.php',
    'SORT' => 100,
  ),
  293 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/productprops/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.productprops',
    'PATH' => '/site_gm/crm/configs/productprops/index.php',
    'SORT' => 100,
  ),
  391 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/productprops/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.productprops',
    'PATH' => '/site_rj/crm/configs/productprops/index.php',
    'SORT' => 100,
  ),
  485 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/mailtemplate/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.mail_template',
    'PATH' => '/site_pq/crm/configs/mailtemplate/index.php',
    'SORT' => 100,
  ),
  489 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/productprops/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.productprops',
    'PATH' => '/site_pq/crm/configs/productprops/index.php',
    'SORT' => 100,
  ),
  195 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/productprops/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.productprops',
    'PATH' => '/site_qn/crm/configs/productprops/index.php',
    'SORT' => 100,
  ),
  289 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/mailtemplate/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.mail_template',
    'PATH' => '/site_gm/crm/configs/mailtemplate/index.php',
    'SORT' => 100,
  ),
  36 => 
  array (
    'CONDITION' => '#^/tasks/getfile/(\\d+)/(\\d+)/([^/]+)#',
    'RULE' => 'taskid=$1&fileid=$2&filename=$3',
    'ID' => NULL,
    'PATH' => '/tasks/getfile.php',
    'SORT' => 100,
  ),
  191 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/mailtemplate/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.mail_template',
    'PATH' => '/site_qn/crm/configs/mailtemplate/index.php',
    'SORT' => 100,
  ),
  387 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/mailtemplate/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.mail_template',
    'PATH' => '/site_rj/crm/configs/mailtemplate/index.php',
    'SORT' => 100,
  ),
  119 => 
  array (
    'CONDITION' => '#^/timeman/login-history/([0-9]+)/.*#',
    'RULE' => 'user=$1',
    'ID' => 'bitrix:intranet.user.login.history',
    'PATH' => '/timeman/login-history/index.php',
    'SORT' => 100,
  ),
  574 => 
  array (
    'CONDITION' => '#^/disk/boards/([0-9]+)/openDocument#',
    'RULE' => 'action=disk.integration.flipchart.openDocument&fileId=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/main/ajax.php',
    'SORT' => 100,
  ),
  575 => 
  array (
    'CONDITION' => '#^/disk/boards/([0-9]+)/openAttached#',
    'RULE' => 'action=disk.integration.flipchart.openAttachedDocument&attachedObjectId=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/main/ajax.php',
    'SORT' => 100,
  ),
  106 => 
  array (
    'CONDITION' => '#^/crm/configs/document_numerators/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.document_numerators.list',
    'PATH' => '/crm/configs/document_numerators/index.php',
    'SORT' => 100,
  ),
  377 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/automation/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.automation',
    'PATH' => '/site_rj/crm/configs/automation/index.php',
    'SORT' => 100,
  ),
  367 => 
  array (
    'CONDITION' => '#^/site_rj/shop/documents-catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.catalog.controller',
    'PATH' => '/site_rj/shop/documents-catalog/index.php',
    'SORT' => 100,
  ),
  355 => 
  array (
    'CONDITION' => '#^/site_rj/mobile/knowledge/group/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_rj/mobile/knowledge/group/index.php',
    'SORT' => 100,
  ),
  171 => 
  array (
    'CONDITION' => '#^/site_qn/shop/documents-catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.catalog.controller',
    'PATH' => '/site_qn/shop/documents-catalog/index.php',
    'SORT' => 100,
  ),
  269 => 
  array (
    'CONDITION' => '#^/site_gm/shop/documents-catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.catalog.controller',
    'PATH' => '/site_gm/shop/documents-catalog/index.php',
    'SORT' => 100,
  ),
  475 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/automation/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.automation',
    'PATH' => '/site_pq/crm/configs/automation/index.php',
    'SORT' => 100,
  ),
  279 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/automation/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.automation',
    'PATH' => '/site_gm/crm/configs/automation/index.php',
    'SORT' => 100,
  ),
  465 => 
  array (
    'CONDITION' => '#^/site_pq/shop/documents-catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.catalog.controller',
    'PATH' => '/site_pq/shop/documents-catalog/index.php',
    'SORT' => 100,
  ),
  257 => 
  array (
    'CONDITION' => '#^/site_gm/mobile/knowledge/group/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_gm/mobile/knowledge/group/index.php',
    'SORT' => 100,
  ),
  181 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/automation/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.automation',
    'PATH' => '/site_qn/crm/configs/automation/index.php',
    'SORT' => 100,
  ),
  453 => 
  array (
    'CONDITION' => '#^/site_pq/mobile/knowledge/group/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_pq/mobile/knowledge/group/index.php',
    'SORT' => 100,
  ),
  159 => 
  array (
    'CONDITION' => '#^/site_qn/mobile/knowledge/group/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_qn/mobile/knowledge/group/index.php',
    'SORT' => 100,
  ),
  584 => 
  array (
    'CONDITION' => '#^/extranet/task/comments/([0-9]+)#',
    'RULE' => 'taskId=$1',
    'ID' => NULL,
    'PATH' => '/extranet/tasks/comments.php',
    'SORT' => 100,
  ),
  411 => 
  array (
    'CONDITION' => '#^/site_rj/marketing/config/role/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_rj/marketing/config/role.php',
    'SORT' => 100,
  ),
  368 => 
  array (
    'CONDITION' => '#^/site_rj/shop/documents-stores/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store.entity.controller',
    'PATH' => '/site_rj/shop/documents-stores/index.php',
    'SORT' => 100,
  ),
  286 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/locations/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.locations',
    'PATH' => '/site_gm/crm/configs/locations/index.php',
    'SORT' => 100,
  ),
  493 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/exclusion/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_pq/crm/configs/exclusion/index.php',
    'SORT' => 100,
  ),
  352 => 
  array (
    'CONDITION' => '#^/site_rj/shop/import/instagram/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.import.instagram',
    'PATH' => '/site_rj/shop/import/instagram/index.php',
    'SORT' => 100,
  ),
  299 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/mycompany/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.company',
    'PATH' => '/site_gm/crm/configs/mycompany/index.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/stssync/contacts_extranet_emp/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/contacts_extranet_emp/index.php',
    'SORT' => 100,
  ),
  215 => 
  array (
    'CONDITION' => '#^/site_qn/marketing/config/role/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_qn/marketing/config/role.php',
    'SORT' => 100,
  ),
  201 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/mycompany/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.company',
    'PATH' => '/site_qn/crm/configs/mycompany/index.php',
    'SORT' => 100,
  ),
  450 => 
  array (
    'CONDITION' => '#^/site_pq/shop/import/instagram/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.import.instagram',
    'PATH' => '/site_pq/shop/import/instagram/index.php',
    'SORT' => 100,
  ),
  509 => 
  array (
    'CONDITION' => '#^/site_pq/marketing/config/role/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_pq/marketing/config/role.php',
    'SORT' => 100,
  ),
  495 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/mycompany/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.company',
    'PATH' => '/site_pq/crm/configs/mycompany/index.php',
    'SORT' => 100,
  ),
  270 => 
  array (
    'CONDITION' => '#^/site_gm/shop/documents-stores/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store.entity.controller',
    'PATH' => '/site_gm/shop/documents-stores/index.php',
    'SORT' => 100,
  ),
  482 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/locations/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.locations',
    'PATH' => '/site_pq/crm/configs/locations/index.php',
    'SORT' => 100,
  ),
  188 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/locations/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.locations',
    'PATH' => '/site_qn/crm/configs/locations/index.php',
    'SORT' => 100,
  ),
  395 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/exclusion/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_rj/crm/configs/exclusion/index.php',
    'SORT' => 100,
  ),
  384 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/locations/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.locations',
    'PATH' => '/site_rj/crm/configs/locations/index.php',
    'SORT' => 100,
  ),
  397 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/mycompany/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.company',
    'PATH' => '/site_rj/crm/configs/mycompany/index.php',
    'SORT' => 100,
  ),
  297 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/exclusion/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_gm/crm/configs/exclusion/index.php',
    'SORT' => 100,
  ),
  254 => 
  array (
    'CONDITION' => '#^/site_gm/shop/import/instagram/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.import.instagram',
    'PATH' => '/site_gm/shop/import/instagram/index.php',
    'SORT' => 100,
  ),
  172 => 
  array (
    'CONDITION' => '#^/site_qn/shop/documents-stores/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store.entity.controller',
    'PATH' => '/site_qn/shop/documents-stores/index.php',
    'SORT' => 100,
  ),
  313 => 
  array (
    'CONDITION' => '#^/site_gm/marketing/config/role/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_gm/marketing/config/role.php',
    'SORT' => 100,
  ),
  199 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/exclusion/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_qn/crm/configs/exclusion/index.php',
    'SORT' => 100,
  ),
  466 => 
  array (
    'CONDITION' => '#^/site_pq/shop/documents-stores/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store.entity.controller',
    'PATH' => '/site_pq/shop/documents-stores/index.php',
    'SORT' => 100,
  ),
  156 => 
  array (
    'CONDITION' => '#^/site_qn/shop/import/instagram/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.import.instagram',
    'PATH' => '/site_qn/shop/import/instagram/index.php',
    'SORT' => 100,
  ),
  382 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/currency/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.currency',
    'PATH' => '/site_rj/crm/configs/currency/index.php',
    'SORT' => 100,
  ),
  284 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/currency/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.currency',
    'PATH' => '/site_gm/crm/configs/currency/index.php',
    'SORT' => 100,
  ),
  50 => 
  array (
    'CONDITION' => '#^/settings/configs/userconsent/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/configs/userconsent.php',
    'SORT' => 100,
  ),
  480 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/currency/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.currency',
    'PATH' => '/site_pq/crm/configs/currency/index.php',
    'SORT' => 100,
  ),
  186 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/currency/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.currency',
    'PATH' => '/site_qn/crm/configs/currency/index.php',
    'SORT' => 100,
  ),
  566 => 
  array (
    'CONDITION' => '#^/bi/dashboard/detail/([0-9]+)/#',
    'RULE' => 'dashboardId=$1',
    'ID' => 'bitrix:biconnector.apachesuperset.dashboard.detail',
    'PATH' => '/bi/dashboard/detail/index.php',
    'SORT' => 100,
  ),
  572 => 
  array (
    'CONDITION' => '#^/extranet/call/detail/([0-9]+)#',
    'RULE' => 'callId=$1',
    'ID' => 'bitrix:call',
    'PATH' => '/extranet/call/index.php',
    'SORT' => 100,
  ),
  193 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/measure/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.measure',
    'PATH' => '/site_qn/crm/configs/measure/index.php',
    'SORT' => 100,
  ),
  389 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/measure/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.measure',
    'PATH' => '/site_rj/crm/configs/measure/index.php',
    'SORT' => 100,
  ),
  17 => 
  array (
    'CONDITION' => '#^\\/?\\/mobile/jn/(.*)\\/(.*)\\/.*#',
    'RULE' => 'componentName=$2&namespace=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobile/jscomponent.php',
    'SORT' => 100,
  ),
  487 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/measure/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.measure',
    'PATH' => '/site_pq/crm/configs/measure/index.php',
    'SORT' => 100,
  ),
  505 => 
  array (
    'CONDITION' => '#^/site_pq/marketing/blacklist/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_pq/marketing/blacklist.php',
    'SORT' => 100,
  ),
  291 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/measure/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.measure',
    'PATH' => '/site_gm/crm/configs/measure/index.php',
    'SORT' => 100,
  ),
  407 => 
  array (
    'CONDITION' => '#^/site_rj/marketing/blacklist/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_rj/marketing/blacklist.php',
    'SORT' => 100,
  ),
  309 => 
  array (
    'CONDITION' => '#^/site_gm/marketing/blacklist/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_gm/marketing/blacklist.php',
    'SORT' => 100,
  ),
  211 => 
  array (
    'CONDITION' => '#^/site_qn/marketing/blacklist/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_qn/marketing/blacklist.php',
    'SORT' => 100,
  ),
  556 => 
  array (
    'CONDITION' => '#^/extranet/helpdesk/equipment/#',
    'RULE' => '',
    'ID' => 'darneo:equipment',
    'PATH' => '/extranet/helpdesk/equipment/index.php',
    'SORT' => 100,
  ),
  290 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/exch1c/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.exch1c',
    'PATH' => '/site_gm/crm/configs/exch1c/index.php',
    'SORT' => 100,
  ),
  294 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/preset/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.preset',
    'PATH' => '/site_gm/crm/configs/preset/index.php',
    'SORT' => 100,
  ),
  308 => 
  array (
    'CONDITION' => '#^/site_gm/marketing/template/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_gm/marketing/template.php',
    'SORT' => 100,
  ),
  194 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/volume/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.volume',
    'PATH' => '/site_qn/crm/configs/volume/index.php',
    'SORT' => 100,
  ),
  292 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/volume/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.volume',
    'PATH' => '/site_gm/crm/configs/volume/index.php',
    'SORT' => 100,
  ),
  392 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/preset/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.preset',
    'PATH' => '/site_rj/crm/configs/preset/index.php',
    'SORT' => 100,
  ),
  474 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/fields/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.fields',
    'PATH' => '/site_pq/crm/configs/fields/index.php',
    'SORT' => 100,
  ),
  386 => 
  array (
    'CONDITION' => '#^/site_rj/crm/reports/report/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.report',
    'PATH' => '/site_rj/crm/reports/report/index.php',
    'SORT' => 100,
  ),
  192 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/exch1c/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.exch1c',
    'PATH' => '/site_qn/crm/configs/exch1c/index.php',
    'SORT' => 100,
  ),
  376 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/fields/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.fields',
    'PATH' => '/site_rj/crm/configs/fields/index.php',
    'SORT' => 100,
  ),
  406 => 
  array (
    'CONDITION' => '#^/site_rj/marketing/template/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_rj/marketing/template.php',
    'SORT' => 100,
  ),
  388 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/exch1c/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.exch1c',
    'PATH' => '/site_rj/crm/configs/exch1c/index.php',
    'SORT' => 100,
  ),
  288 => 
  array (
    'CONDITION' => '#^/site_gm/crm/reports/report/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.report',
    'PATH' => '/site_gm/crm/reports/report/index.php',
    'SORT' => 100,
  ),
  486 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/exch1c/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.exch1c',
    'PATH' => '/site_pq/crm/configs/exch1c/index.php',
    'SORT' => 100,
  ),
  390 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/volume/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.volume',
    'PATH' => '/site_rj/crm/configs/volume/index.php',
    'SORT' => 100,
  ),
  504 => 
  array (
    'CONDITION' => '#^/site_pq/marketing/template/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_pq/marketing/template.php',
    'SORT' => 100,
  ),
  190 => 
  array (
    'CONDITION' => '#^/site_qn/crm/reports/report/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.report',
    'PATH' => '/site_qn/crm/reports/report/index.php',
    'SORT' => 100,
  ),
  210 => 
  array (
    'CONDITION' => '#^/site_qn/marketing/template/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_qn/marketing/template.php',
    'SORT' => 100,
  ),
  488 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/volume/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.volume',
    'PATH' => '/site_pq/crm/configs/volume/index.php',
    'SORT' => 100,
  ),
  180 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/fields/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.fields',
    'PATH' => '/site_qn/crm/configs/fields/index.php',
    'SORT' => 100,
  ),
  278 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/fields/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.fields',
    'PATH' => '/site_gm/crm/configs/fields/index.php',
    'SORT' => 100,
  ),
  196 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/preset/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.preset',
    'PATH' => '/site_qn/crm/configs/preset/index.php',
    'SORT' => 100,
  ),
  490 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/preset/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.preset',
    'PATH' => '/site_pq/crm/configs/preset/index.php',
    'SORT' => 100,
  ),
  484 => 
  array (
    'CONDITION' => '#^/site_pq/crm/reports/report/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.report',
    'PATH' => '/site_pq/crm/reports/report/index.php',
    'SORT' => 100,
  ),
  519 => 
  array (
    'CONDITION' => '#^/extranet/workgroups/create/#',
    'RULE' => NULL,
    'ID' => 'bitrix:extranet.group_create',
    'PATH' => '/extranet/workgroups/create/index.php',
    'SORT' => 100,
  ),
  520 => 
  array (
    'CONDITION' => '#^/extranet/contacts/personal/#',
    'RULE' => NULL,
    'ID' => 'bitrix:socialnetwork_user',
    'PATH' => '/extranet/contacts/personal.php',
    'SORT' => 100,
  ),
  528 => 
  array (
    'CONDITION' => '#^/pub/payment-slip/([\\w\\W]+)/#',
    'RULE' => 'signed_payment_id=$1',
    'ID' => 'bitrix:salescenter.pub.payment.slip',
    'PATH' => '/pub/payment_slip.php',
    'SORT' => 100,
  ),
  555 => 
  array (
    'CONDITION' => '#^/extranet/helpdesk/contract/#',
    'RULE' => '',
    'ID' => 'darneo:contract',
    'PATH' => '/extranet/helpdesk/contract/index.php',
    'SORT' => 100,
  ),
  242 => 
  array (
    'CONDITION' => '#^/site_gm/marketplace/local/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.marketplace.localapp',
    'PATH' => '/site_gm/marketplace/local/index.php',
    'SORT' => 100,
  ),
  9 => 
  array (
    'CONDITION' => '#^/stssync/calendar_extranet/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/calendar_extranet/index.php',
    'SORT' => 100,
  ),
  20 => 
  array (
    'CONDITION' => '#^\\/?\\/mobileapp/jn\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobileapp/jn.php',
    'SORT' => 100,
  ),
  446 => 
  array (
    'CONDITION' => '#^/site_pq/timeman/schedules/#',
    'RULE' => '',
    'ID' => 'bitrix:timeman.schedules',
    'PATH' => '/site_pq/timeman/schedules.php',
    'SORT' => 100,
  ),
  427 => 
  array (
    'CONDITION' => '#^/site_pq/bizproc/processes/#',
    'RULE' => '',
    'ID' => 'bitrix:lists',
    'PATH' => '/site_pq/bizproc/processes/index.php',
    'SORT' => 100,
  ),
  6 => 
  array (
    'CONDITION' => '#^/stssync/contacts_extranet/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/contacts_extranet/index.php',
    'SORT' => 100,
  ),
  408 => 
  array (
    'CONDITION' => '#^/site_rj/marketing/contact/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_rj/marketing/contact.php',
    'SORT' => 100,
  ),
  307 => 
  array (
    'CONDITION' => '#^/site_gm/marketing/segment/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_gm/marketing/segment.php',
    'SORT' => 100,
  ),
  26 => 
  array (
    'CONDITION' => '#^/marketplace/configuration/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.configuration',
    'PATH' => '/marketplace/configuration/index.php',
    'SORT' => 100,
  ),
  503 => 
  array (
    'CONDITION' => '#^/site_pq/marketing/segment/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_pq/marketing/segment.php',
    'SORT' => 100,
  ),
  183 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/perms/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.perms',
    'PATH' => '/site_qn/crm/configs/perms/index.php',
    'SORT' => 100,
  ),
  231 => 
  array (
    'CONDITION' => '#^/site_gm/bizproc/processes/#',
    'RULE' => '',
    'ID' => 'bitrix:lists',
    'PATH' => '/site_gm/bizproc/processes/index.php',
    'SORT' => 100,
  ),
  413 => 
  array (
    'CONDITION' => '#^/site_rj/online/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_rj/desktop_app/router.php',
    'SORT' => 100,
  ),
  405 => 
  array (
    'CONDITION' => '#^/site_rj/marketing/segment/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_rj/marketing/segment.php',
    'SORT' => 100,
  ),
  152 => 
  array (
    'CONDITION' => '#^/site_qn/timeman/schedules/#',
    'RULE' => '',
    'ID' => 'bitrix:timeman.schedules',
    'PATH' => '/site_qn/timeman/schedules.php',
    'SORT' => 100,
  ),
  511 => 
  array (
    'CONDITION' => '#^/site_pq/online/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_pq/desktop_app/router.php',
    'SORT' => 100,
  ),
  250 => 
  array (
    'CONDITION' => '#^/site_gm/timeman/schedules/#',
    'RULE' => '',
    'ID' => 'bitrix:timeman.schedules',
    'PATH' => '/site_gm/timeman/schedules.php',
    'SORT' => 100,
  ),
  133 => 
  array (
    'CONDITION' => '#^/site_qn/bizproc/processes/#',
    'RULE' => '',
    'ID' => 'bitrix:lists',
    'PATH' => '/site_qn/bizproc/processes/index.php',
    'SORT' => 100,
  ),
  477 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/perms/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.perms',
    'PATH' => '/site_pq/crm/configs/perms/index.php',
    'SORT' => 100,
  ),
  310 => 
  array (
    'CONDITION' => '#^/site_gm/marketing/contact/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_gm/marketing/contact.php',
    'SORT' => 100,
  ),
  281 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/perms/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.perms',
    'PATH' => '/site_gm/crm/configs/perms/index.php',
    'SORT' => 100,
  ),
  348 => 
  array (
    'CONDITION' => '#^/site_rj/timeman/schedules/#',
    'RULE' => '',
    'ID' => 'bitrix:timeman.schedules',
    'PATH' => '/site_rj/timeman/schedules.php',
    'SORT' => 100,
  ),
  212 => 
  array (
    'CONDITION' => '#^/site_qn/marketing/contact/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_qn/marketing/contact.php',
    'SORT' => 100,
  ),
  438 => 
  array (
    'CONDITION' => '#^/site_pq/marketplace/local/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.marketplace.localapp',
    'PATH' => '/site_pq/marketplace/local/index.php',
    'SORT' => 100,
  ),
  340 => 
  array (
    'CONDITION' => '#^/site_rj/marketplace/local/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.marketplace.localapp',
    'PATH' => '/site_rj/marketplace/local/index.php',
    'SORT' => 100,
  ),
  315 => 
  array (
    'CONDITION' => '#^/site_gm/online/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_gm/desktop_app/router.php',
    'SORT' => 100,
  ),
  506 => 
  array (
    'CONDITION' => '#^/site_pq/marketing/contact/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_pq/marketing/contact.php',
    'SORT' => 100,
  ),
  379 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/perms/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.perms',
    'PATH' => '/site_rj/crm/configs/perms/index.php',
    'SORT' => 100,
  ),
  144 => 
  array (
    'CONDITION' => '#^/site_qn/marketplace/local/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.marketplace.localapp',
    'PATH' => '/site_qn/marketplace/local/index.php',
    'SORT' => 100,
  ),
  329 => 
  array (
    'CONDITION' => '#^/site_rj/bizproc/processes/#',
    'RULE' => '',
    'ID' => 'bitrix:lists',
    'PATH' => '/site_rj/bizproc/processes/index.php',
    'SORT' => 100,
  ),
  217 => 
  array (
    'CONDITION' => '#^/site_qn/online/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_qn/desktop_app/router.php',
    'SORT' => 100,
  ),
  209 => 
  array (
    'CONDITION' => '#^/site_qn/marketing/segment/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_qn/marketing/segment.php',
    'SORT' => 100,
  ),
  104 => 
  array (
    'CONDITION' => '#^/crm/configs/deal_category/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.deal_category',
    'PATH' => '/crm/configs/deal_category/index.php',
    'SORT' => 100,
  ),
  536 => 
  array (
    'CONDITION' => '#^/sign/link/member/([0-9]+)/#',
    'RULE' => 'memberId=$1',
    'ID' => '',
    'PATH' => '/sign/link.php',
    'SORT' => 100,
  ),
  551 => 
  array (
    'CONDITION' => '#^/extranet/helpdesk/tickets/#',
    'RULE' => '',
    'ID' => 'darneo:tickets',
    'PATH' => '/extranet/helpdesk/tickets/index.php',
    'SORT' => 100,
  ),
  552 => 
  array (
    'CONDITION' => '#^/extranet/helpdesk/company/#',
    'RULE' => '',
    'ID' => 'darneo:company',
    'PATH' => '/extranet/helpdesk/company/index.php',
    'SORT' => 100,
  ),
  578 => 
  array (
    'CONDITION' => '#^/shop/settings/permissions/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store.entity.controller',
    'PATH' => '/shop/settings/permissions/index.php',
    'SORT' => 100,
  ),
  160 => 
  array (
    'CONDITION' => '#^/site_qn/mobile/knowledge/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_qn/mobile/knowledge/index.php',
    'SORT' => 100,
  ),
  444 => 
  array (
    'CONDITION' => '#^/site_pq/timeman/worktime/#',
    'RULE' => '',
    'ID' => 'bitrix:timeman.worktime',
    'PATH' => '/site_pq/timeman/worktime.php',
    'SORT' => 100,
  ),
  312 => 
  array (
    'CONDITION' => '#^/site_gm/marketing/toloka/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_gm/marketing/toloka.php',
    'SORT' => 100,
  ),
  415 => 
  array (
    'CONDITION' => '#^/site_pq/company/personal/#',
    'RULE' => '',
    'ID' => 'bitrix:socialnetwork_user',
    'PATH' => '/site_pq/company/personal.php',
    'SORT' => 100,
  ),
  244 => 
  array (
    'CONDITION' => '#^/site_gm/marketplace/hook/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.hook',
    'PATH' => '/site_gm/marketplace/hook/index.php',
    'SORT' => 100,
  ),
  305 => 
  array (
    'CONDITION' => '#^/site_gm/marketing/letter/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_gm/marketing/letter.php',
    'SORT' => 100,
  ),
  248 => 
  array (
    'CONDITION' => '#^/site_gm/timeman/worktime/#',
    'RULE' => '',
    'ID' => 'bitrix:timeman.worktime',
    'PATH' => '/site_gm/timeman/worktime.php',
    'SORT' => 100,
  ),
  219 => 
  array (
    'CONDITION' => '#^/site_gm/company/personal/#',
    'RULE' => '',
    'ID' => 'bitrix:socialnetwork_user',
    'PATH' => '/site_gm/company/personal.php',
    'SORT' => 100,
  ),
  146 => 
  array (
    'CONDITION' => '#^/site_qn/marketplace/hook/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.hook',
    'PATH' => '/site_qn/marketplace/hook/index.php',
    'SORT' => 100,
  ),
  454 => 
  array (
    'CONDITION' => '#^/site_pq/mobile/knowledge/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_pq/mobile/knowledge/index.php',
    'SORT' => 100,
  ),
  350 => 
  array (
    'CONDITION' => '#^/site_rj/shop/buyer_group/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.buyer_group',
    'PATH' => '/site_rj/shop/buyer_group/index.php',
    'SORT' => 100,
  ),
  258 => 
  array (
    'CONDITION' => '#^/site_gm/mobile/knowledge/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_gm/mobile/knowledge/index.php',
    'SORT' => 100,
  ),
  249 => 
  array (
    'CONDITION' => '#^/site_gm/timeman/settings/#',
    'RULE' => '',
    'ID' => 'bitrix:timeman.settings',
    'PATH' => '/site_gm/timeman/settings.php',
    'SORT' => 100,
  ),
  403 => 
  array (
    'CONDITION' => '#^/site_rj/marketing/letter/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_rj/marketing/letter.php',
    'SORT' => 100,
  ),
  440 => 
  array (
    'CONDITION' => '#^/site_pq/marketplace/hook/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.hook',
    'PATH' => '/site_pq/marketplace/hook/index.php',
    'SORT' => 100,
  ),
  214 => 
  array (
    'CONDITION' => '#^/site_qn/marketing/toloka/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_qn/marketing/toloka.php',
    'SORT' => 100,
  ),
  207 => 
  array (
    'CONDITION' => '#^/site_qn/marketing/letter/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_qn/marketing/letter.php',
    'SORT' => 100,
  ),
  252 => 
  array (
    'CONDITION' => '#^/site_gm/shop/buyer_group/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.buyer_group',
    'PATH' => '/site_gm/shop/buyer_group/index.php',
    'SORT' => 100,
  ),
  97 => 
  array (
    'CONDITION' => '#^/crm/configs/productprops/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.productprops',
    'PATH' => '/crm/configs/productprops/index.php',
    'SORT' => 100,
  ),
  317 => 
  array (
    'CONDITION' => '#^/site_rj/company/personal/#',
    'RULE' => '',
    'ID' => 'bitrix:socialnetwork_user',
    'PATH' => '/site_rj/company/personal.php',
    'SORT' => 100,
  ),
  154 => 
  array (
    'CONDITION' => '#^/site_qn/shop/buyer_group/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.buyer_group',
    'PATH' => '/site_qn/shop/buyer_group/index.php',
    'SORT' => 100,
  ),
  150 => 
  array (
    'CONDITION' => '#^/site_qn/timeman/worktime/#',
    'RULE' => '',
    'ID' => 'bitrix:timeman.worktime',
    'PATH' => '/site_qn/timeman/worktime.php',
    'SORT' => 100,
  ),
  410 => 
  array (
    'CONDITION' => '#^/site_rj/marketing/toloka/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_rj/marketing/toloka.php',
    'SORT' => 100,
  ),
  508 => 
  array (
    'CONDITION' => '#^/site_pq/marketing/toloka/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_pq/marketing/toloka.php',
    'SORT' => 100,
  ),
  347 => 
  array (
    'CONDITION' => '#^/site_rj/timeman/settings/#',
    'RULE' => '',
    'ID' => 'bitrix:timeman.settings',
    'PATH' => '/site_rj/timeman/settings.php',
    'SORT' => 100,
  ),
  121 => 
  array (
    'CONDITION' => '#^/site_qn/company/personal/#',
    'RULE' => '',
    'ID' => 'bitrix:socialnetwork_user',
    'PATH' => '/site_qn/company/personal.php',
    'SORT' => 100,
  ),
  93 => 
  array (
    'CONDITION' => '#^/crm/configs/mailtemplate/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.mail_template',
    'PATH' => '/crm/configs/mailtemplate/index.php',
    'SORT' => 100,
  ),
  346 => 
  array (
    'CONDITION' => '#^/site_rj/timeman/worktime/#',
    'RULE' => '',
    'ID' => 'bitrix:timeman.worktime',
    'PATH' => '/site_rj/timeman/worktime.php',
    'SORT' => 100,
  ),
  151 => 
  array (
    'CONDITION' => '#^/site_qn/timeman/settings/#',
    'RULE' => '',
    'ID' => 'bitrix:timeman.settings',
    'PATH' => '/site_qn/timeman/settings.php',
    'SORT' => 100,
  ),
  448 => 
  array (
    'CONDITION' => '#^/site_pq/shop/buyer_group/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.buyer_group',
    'PATH' => '/site_pq/shop/buyer_group/index.php',
    'SORT' => 100,
  ),
  501 => 
  array (
    'CONDITION' => '#^/site_pq/marketing/letter/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_pq/marketing/letter.php',
    'SORT' => 100,
  ),
  342 => 
  array (
    'CONDITION' => '#^/site_rj/marketplace/hook/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.hook',
    'PATH' => '/site_rj/marketplace/hook/index.php',
    'SORT' => 100,
  ),
  445 => 
  array (
    'CONDITION' => '#^/site_pq/timeman/settings/#',
    'RULE' => '',
    'ID' => 'bitrix:timeman.settings',
    'PATH' => '/site_pq/timeman/settings.php',
    'SORT' => 100,
  ),
  356 => 
  array (
    'CONDITION' => '#^/site_rj/mobile/knowledge/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_rj/mobile/knowledge/index.php',
    'SORT' => 100,
  ),
  522 => 
  array (
    'CONDITION' => '#^/extranet/marketplace/app/#',
    'RULE' => NULL,
    'ID' => 'bitrix:app.layout',
    'PATH' => '/extranet/marketplace/app/index.php',
    'SORT' => 100,
  ),
  553 => 
  array (
    'CONDITION' => '#^/extranet/helpdesk/client/#',
    'RULE' => '',
    'ID' => 'darneo:client',
    'PATH' => '/extranet/helpdesk/client/index.php',
    'SORT' => 100,
  ),
  554 => 
  array (
    'CONDITION' => '#^/extranet/helpdesk/object/#',
    'RULE' => '',
    'ID' => 'darneo:object',
    'PATH' => '/extranet/helpdesk/object/index.php',
    'SORT' => 100,
  ),
  576 => 
  array (
    'CONDITION' => '#^/disk/boards/([0-9]+)/open#',
    'RULE' => 'action=disk.integration.flipchart.openDocument&fileId=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/main/ajax.php',
    'SORT' => 100,
  ),
  577 => 
  array (
    'CONDITION' => '#^/vote-result/([0-9a-z\\.]+)#',
    'RULE' => 'signedAttachId=$1',
    'ID' => NULL,
    'PATH' => '/vote-result/index.php',
    'SORT' => 100,
  ),
  383 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/tax/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.tax',
    'PATH' => '/site_rj/crm/configs/tax/index.php',
    'SORT' => 100,
  ),
  120 => 
  array (
    'CONDITION' => '#^/site_qn/company/gallery/#',
    'RULE' => '',
    'ID' => 'bitrix:photogallery_user',
    'PATH' => '/site_qn/company/gallery/index.php',
    'SORT' => 100,
  ),
  206 => 
  array (
    'CONDITION' => '#^/site_qn/timeman/meeting/#',
    'RULE' => '',
    'ID' => 'bitrix:meetings',
    'PATH' => '/site_qn/timeman/meeting/index.php',
    'SORT' => 100,
  ),
  316 => 
  array (
    'CONDITION' => '#^/site_rj/company/gallery/#',
    'RULE' => '',
    'ID' => 'bitrix:photogallery_user',
    'PATH' => '/site_rj/company/gallery/index.php',
    'SORT' => 100,
  ),
  255 => 
  array (
    'CONDITION' => '#^/site_gm/knowledge/group/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_gm/knowledge/group/index.php',
    'SORT' => 100,
  ),
  341 => 
  array (
    'CONDITION' => '#^/site_rj/marketplace/app/#',
    'RULE' => '',
    'ID' => 'bitrix:app.layout',
    'PATH' => '/site_rj/marketplace/app/index.php',
    'SORT' => 100,
  ),
  187 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/tax/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.tax',
    'PATH' => '/site_qn/crm/configs/tax/index.php',
    'SORT' => 100,
  ),
  353 => 
  array (
    'CONDITION' => '#^/site_rj/knowledge/group/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_rj/knowledge/group/index.php',
    'SORT' => 100,
  ),
  218 => 
  array (
    'CONDITION' => '#^/site_gm/company/gallery/#',
    'RULE' => '',
    'ID' => 'bitrix:photogallery_user',
    'PATH' => '/site_gm/company/gallery/index.php',
    'SORT' => 100,
  ),
  439 => 
  array (
    'CONDITION' => '#^/site_pq/marketplace/app/#',
    'RULE' => '',
    'ID' => 'bitrix:app.layout',
    'PATH' => '/site_pq/marketplace/app/index.php',
    'SORT' => 100,
  ),
  451 => 
  array (
    'CONDITION' => '#^/site_pq/knowledge/group/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_pq/knowledge/group/index.php',
    'SORT' => 100,
  ),
  157 => 
  array (
    'CONDITION' => '#^/site_qn/knowledge/group/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_qn/knowledge/group/index.php',
    'SORT' => 100,
  ),
  414 => 
  array (
    'CONDITION' => '#^/site_pq/company/gallery/#',
    'RULE' => '',
    'ID' => 'bitrix:photogallery_user',
    'PATH' => '/site_pq/company/gallery/index.php',
    'SORT' => 100,
  ),
  243 => 
  array (
    'CONDITION' => '#^/site_gm/marketplace/app/#',
    'RULE' => '',
    'ID' => 'bitrix:app.layout',
    'PATH' => '/site_gm/marketplace/app/index.php',
    'SORT' => 100,
  ),
  500 => 
  array (
    'CONDITION' => '#^/site_pq/timeman/meeting/#',
    'RULE' => '',
    'ID' => 'bitrix:meetings',
    'PATH' => '/site_pq/timeman/meeting/index.php',
    'SORT' => 100,
  ),
  481 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/tax/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.tax',
    'PATH' => '/site_pq/crm/configs/tax/index.php',
    'SORT' => 100,
  ),
  145 => 
  array (
    'CONDITION' => '#^/site_qn/marketplace/app/#',
    'RULE' => '',
    'ID' => 'bitrix:app.layout',
    'PATH' => '/site_qn/marketplace/app/index.php',
    'SORT' => 100,
  ),
  304 => 
  array (
    'CONDITION' => '#^/site_gm/timeman/meeting/#',
    'RULE' => '',
    'ID' => 'bitrix:meetings',
    'PATH' => '/site_gm/timeman/meeting/index.php',
    'SORT' => 100,
  ),
  27 => 
  array (
    'CONDITION' => '#^/bitrix/services/ymarket/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/bitrix/services/ymarket/index.php',
    'SORT' => 100,
  ),
  402 => 
  array (
    'CONDITION' => '#^/site_rj/timeman/meeting/#',
    'RULE' => '',
    'ID' => 'bitrix:meetings',
    'PATH' => '/site_rj/timeman/meeting/index.php',
    'SORT' => 100,
  ),
  285 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/tax/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.tax',
    'PATH' => '/site_gm/crm/configs/tax/index.php',
    'SORT' => 100,
  ),
  512 => 
  array (
    'CONDITION' => '#^/marketing/master-yandex/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/marketing/master-yandex.php',
    'SORT' => 100,
  ),
  83 => 
  array (
    'CONDITION' => '#^/crm/configs/automation/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.automation',
    'PATH' => '/crm/configs/automation/index.php',
    'SORT' => 100,
  ),
  170 => 
  array (
    'CONDITION' => '#^/site_qn/shop/documents/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store.document',
    'PATH' => '/site_qn/shop/documents/index.php',
    'SORT' => 100,
  ),
  222 => 
  array (
    'CONDITION' => '#^/site_gm/services/lists/#',
    'RULE' => '',
    'ID' => 'bitrix:lists',
    'PATH' => '/site_gm/services/lists/index.php',
    'SORT' => 100,
  ),
  365 => 
  array (
    'CONDITION' => '#^/site_rj/calendar/rooms/#',
    'RULE' => '',
    'ID' => 'bitrix:calender',
    'PATH' => '/site_rj/calendar/rooms.php',
    'SORT' => 100,
  ),
  124 => 
  array (
    'CONDITION' => '#^/site_qn/services/lists/#',
    'RULE' => '',
    'ID' => 'bitrix:lists',
    'PATH' => '/site_qn/services/lists/index.php',
    'SORT' => 100,
  ),
  268 => 
  array (
    'CONDITION' => '#^/site_gm/shop/documents/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store.document',
    'PATH' => '/site_gm/shop/documents/index.php',
    'SORT' => 100,
  ),
  267 => 
  array (
    'CONDITION' => '#^/site_gm/calendar/rooms/#',
    'RULE' => '',
    'ID' => 'bitrix:calender',
    'PATH' => '/site_gm/calendar/rooms.php',
    'SORT' => 100,
  ),
  418 => 
  array (
    'CONDITION' => '#^/site_pq/services/lists/#',
    'RULE' => '',
    'ID' => 'bitrix:lists',
    'PATH' => '/site_pq/services/lists/index.php',
    'SORT' => 100,
  ),
  385 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/ps/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.ps',
    'PATH' => '/site_rj/crm/configs/ps/index.php',
    'SORT' => 100,
  ),
  483 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/ps/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.ps',
    'PATH' => '/site_pq/crm/configs/ps/index.php',
    'SORT' => 100,
  ),
  447 => 
  array (
    'CONDITION' => '#^/site_pq/shop/orderform/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.matcher',
    'PATH' => '/site_pq/shop/orderform/index.php',
    'SORT' => 100,
  ),
  287 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/ps/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.ps',
    'PATH' => '/site_gm/crm/configs/ps/index.php',
    'SORT' => 100,
  ),
  62 => 
  array (
    'CONDITION' => '#^/mobile/knowledge/group/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/mobile/knowledge/group/index.php',
    'SORT' => 100,
  ),
  349 => 
  array (
    'CONDITION' => '#^/site_rj/shop/orderform/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.matcher',
    'PATH' => '/site_rj/shop/orderform/index.php',
    'SORT' => 100,
  ),
  366 => 
  array (
    'CONDITION' => '#^/site_rj/shop/documents/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store.document',
    'PATH' => '/site_rj/shop/documents/index.php',
    'SORT' => 100,
  ),
  280 => 
  array (
    'CONDITION' => '#^/site_gm/crm/configs/bp/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.bp',
    'PATH' => '/site_gm/crm/configs/bp/index.php',
    'SORT' => 100,
  ),
  182 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/bp/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.bp',
    'PATH' => '/site_qn/crm/configs/bp/index.php',
    'SORT' => 100,
  ),
  16 => 
  array (
    'CONDITION' => '#^\\/?\\/mobile/jn\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobile/jscomponent.php',
    'SORT' => 100,
  ),
  74 => 
  array (
    'CONDITION' => '#^/shop/documents-catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.catalog.controller',
    'PATH' => '/shop/documents-catalog/index.php',
    'SORT' => 100,
  ),
  8 => 
  array (
    'CONDITION' => '#^/stssync/tasks_extranet/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/tasks_extranet/index.php',
    'SORT' => 100,
  ),
  476 => 
  array (
    'CONDITION' => '#^/site_pq/crm/configs/bp/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.bp',
    'PATH' => '/site_pq/crm/configs/bp/index.php',
    'SORT' => 100,
  ),
  153 => 
  array (
    'CONDITION' => '#^/site_qn/shop/orderform/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.matcher',
    'PATH' => '/site_qn/shop/orderform/index.php',
    'SORT' => 100,
  ),
  378 => 
  array (
    'CONDITION' => '#^/site_rj/crm/configs/bp/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.bp',
    'PATH' => '/site_rj/crm/configs/bp/index.php',
    'SORT' => 100,
  ),
  463 => 
  array (
    'CONDITION' => '#^/site_pq/calendar/rooms/#',
    'RULE' => '',
    'ID' => 'bitrix:calender',
    'PATH' => '/site_pq/calendar/rooms.php',
    'SORT' => 100,
  ),
  169 => 
  array (
    'CONDITION' => '#^/site_qn/calendar/rooms/#',
    'RULE' => '',
    'ID' => 'bitrix:calender',
    'PATH' => '/site_qn/calendar/rooms.php',
    'SORT' => 100,
  ),
  320 => 
  array (
    'CONDITION' => '#^/site_rj/services/lists/#',
    'RULE' => '',
    'ID' => 'bitrix:lists',
    'PATH' => '/site_rj/services/lists/index.php',
    'SORT' => 100,
  ),
  251 => 
  array (
    'CONDITION' => '#^/site_gm/shop/orderform/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.matcher',
    'PATH' => '/site_gm/shop/orderform/index.php',
    'SORT' => 100,
  ),
  189 => 
  array (
    'CONDITION' => '#^/site_qn/crm/configs/ps/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.ps',
    'PATH' => '/site_qn/crm/configs/ps/index.php',
    'SORT' => 100,
  ),
  464 => 
  array (
    'CONDITION' => '#^/site_pq/shop/documents/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store.document',
    'PATH' => '/site_pq/shop/documents/index.php',
    'SORT' => 100,
  ),
  562 => 
  array (
    'CONDITION' => '#^/booking/detail/([0-9]+)#',
    'RULE' => 'id=$1',
    'ID' => 'bitrix:booking.booking.detail',
    'PATH' => '/booking/detail.php',
    'SORT' => 100,
  ),
  135 => 
  array (
    'CONDITION' => '#^/site_qn/shop/settings/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.admin.page.controller',
    'PATH' => '/site_qn/shop/settings/index.php',
    'SORT' => 100,
  ),
  90 => 
  array (
    'CONDITION' => '#^/crm/configs/locations/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.locations',
    'PATH' => '/crm/configs/locations/index.php',
    'SORT' => 100,
  ),
  502 => 
  array (
    'CONDITION' => '#^/site_pq/marketing/ads/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_pq/marketing/ads.php',
    'SORT' => 100,
  ),
  103 => 
  array (
    'CONDITION' => '#^/crm/configs/mycompany/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.company',
    'PATH' => '/crm/configs/mycompany/index.php',
    'SORT' => 100,
  ),
  220 => 
  array (
    'CONDITION' => '#^/site_gm/about/gallery/#',
    'RULE' => '',
    'ID' => 'bitrix:photogallery',
    'PATH' => '/site_gm/about/gallery/index.php',
    'SORT' => 100,
  ),
  429 => 
  array (
    'CONDITION' => '#^/site_pq/shop/settings/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.admin.page.controller',
    'PATH' => '/site_pq/shop/settings/index.php',
    'SORT' => 100,
  ),
  306 => 
  array (
    'CONDITION' => '#^/site_gm/marketing/ads/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_gm/marketing/ads.php',
    'SORT' => 100,
  ),
  122 => 
  array (
    'CONDITION' => '#^/site_qn/about/gallery/#',
    'RULE' => '',
    'ID' => 'bitrix:photogallery',
    'PATH' => '/site_qn/about/gallery/index.php',
    'SORT' => 100,
  ),
  126 => 
  array (
    'CONDITION' => '#^/site_qn/services/idea/#',
    'RULE' => '',
    'ID' => 'bitrix:idea',
    'PATH' => '/site_qn/services/idea/index.php',
    'SORT' => 100,
  ),
  420 => 
  array (
    'CONDITION' => '#^/site_pq/services/idea/#',
    'RULE' => '',
    'ID' => 'bitrix:idea',
    'PATH' => '/site_pq/services/idea/index.php',
    'SORT' => 100,
  ),
  318 => 
  array (
    'CONDITION' => '#^/site_rj/about/gallery/#',
    'RULE' => '',
    'ID' => 'bitrix:photogallery',
    'PATH' => '/site_rj/about/gallery/index.php',
    'SORT' => 100,
  ),
  117 => 
  array (
    'CONDITION' => '#^/marketing/config/role/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/marketing/config/role.php',
    'SORT' => 100,
  ),
  233 => 
  array (
    'CONDITION' => '#^/site_gm/shop/settings/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.admin.page.controller',
    'PATH' => '/site_gm/shop/settings/index.php',
    'SORT' => 100,
  ),
  404 => 
  array (
    'CONDITION' => '#^/site_rj/marketing/ads/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_rj/marketing/ads.php',
    'SORT' => 100,
  ),
  322 => 
  array (
    'CONDITION' => '#^/site_rj/services/idea/#',
    'RULE' => '',
    'ID' => 'bitrix:idea',
    'PATH' => '/site_rj/services/idea/index.php',
    'SORT' => 100,
  ),
  101 => 
  array (
    'CONDITION' => '#^/crm/configs/exclusion/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/crm/configs/exclusion/index.php',
    'SORT' => 100,
  ),
  416 => 
  array (
    'CONDITION' => '#^/site_pq/about/gallery/#',
    'RULE' => '',
    'ID' => 'bitrix:photogallery',
    'PATH' => '/site_pq/about/gallery/index.php',
    'SORT' => 100,
  ),
  224 => 
  array (
    'CONDITION' => '#^/site_gm/services/idea/#',
    'RULE' => '',
    'ID' => 'bitrix:idea',
    'PATH' => '/site_gm/services/idea/index.php',
    'SORT' => 100,
  ),
  118 => 
  array (
    'CONDITION' => '#^/shop/documents-stores/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store.entity.controller',
    'PATH' => '/shop/documents-stores/index.php',
    'SORT' => 100,
  ),
  331 => 
  array (
    'CONDITION' => '#^/site_rj/shop/settings/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.admin.page.controller',
    'PATH' => '/site_rj/shop/settings/index.php',
    'SORT' => 100,
  ),
  208 => 
  array (
    'CONDITION' => '#^/site_qn/marketing/ads/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_qn/marketing/ads.php',
    'SORT' => 100,
  ),
  523 => 
  array (
    'CONDITION' => '#^/extranet/mobile/webdav#',
    'RULE' => NULL,
    'ID' => 'bitrix:mobile.webdav.file.list',
    'PATH' => '/extranet/mobile/webdav/index.php',
    'SORT' => 100,
  ),
  582 => 
  array (
    'CONDITION' => '#^/task/comments/([0-9]+)#',
    'RULE' => 'taskId=$1',
    'ID' => NULL,
    'PATH' => '/tasks/comments.php',
    'SORT' => 100,
  ),
  409 => 
  array (
    'CONDITION' => '#^/site_rj/marketing/rc/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_rj/marketing/rc.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/stssync/contacts_crm/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/contacts_crm/index.php',
    'SORT' => 100,
  ),
  173 => 
  array (
    'CONDITION' => '#^/site_qn/shop/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.productcard.controller',
    'PATH' => '/site_qn//shop/catalog/index.php',
    'SORT' => 100,
  ),
  125 => 
  array (
    'CONDITION' => '#^/site_qn/services/faq/#',
    'RULE' => '',
    'ID' => 'bitrix:support.faq',
    'PATH' => '/site_qn/services/faq/index.php',
    'SORT' => 100,
  ),
  271 => 
  array (
    'CONDITION' => '#^/site_gm/shop/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.productcard.controller',
    'PATH' => '/site_gm//shop/catalog/index.php',
    'SORT' => 100,
  ),
  369 => 
  array (
    'CONDITION' => '#^/site_rj/shop/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.productcard.controller',
    'PATH' => '/site_rj//shop/catalog/index.php',
    'SORT' => 100,
  ),
  203 => 
  array (
    'CONDITION' => '#^/site_qn/crm/activity/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.activity',
    'PATH' => '/site_qn/crm/activity/index.php',
    'SORT' => 100,
  ),
  497 => 
  array (
    'CONDITION' => '#^/site_pq/crm/activity/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.activity',
    'PATH' => '/site_pq/crm/activity/index.php',
    'SORT' => 100,
  ),
  419 => 
  array (
    'CONDITION' => '#^/site_pq/services/faq/#',
    'RULE' => '',
    'ID' => 'bitrix:support.faq',
    'PATH' => '/site_pq/services/faq/index.php',
    'SORT' => 100,
  ),
  507 => 
  array (
    'CONDITION' => '#^/site_pq/marketing/rc/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_pq/marketing/rc.php',
    'SORT' => 100,
  ),
  88 => 
  array (
    'CONDITION' => '#^/crm/configs/currency/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.currency',
    'PATH' => '/crm/configs/currency/index.php',
    'SORT' => 100,
  ),
  213 => 
  array (
    'CONDITION' => '#^/site_qn/marketing/rc/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_qn/marketing/rc.php',
    'SORT' => 100,
  ),
  467 => 
  array (
    'CONDITION' => '#^/site_pq/shop/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.productcard.controller',
    'PATH' => '/site_pq//shop/catalog/index.php',
    'SORT' => 100,
  ),
  396 => 
  array (
    'CONDITION' => '#^/site_rj/crm/tracking/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_rj/crm/tracking/index.php',
    'SORT' => 100,
  ),
  223 => 
  array (
    'CONDITION' => '#^/site_gm/services/faq/#',
    'RULE' => '',
    'ID' => 'bitrix:support.faq',
    'PATH' => '/site_gm/services/faq/index.php',
    'SORT' => 100,
  ),
  298 => 
  array (
    'CONDITION' => '#^/site_gm/crm/tracking/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_gm/crm/tracking/index.php',
    'SORT' => 100,
  ),
  311 => 
  array (
    'CONDITION' => '#^/site_gm/marketing/rc/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_gm/marketing/rc.php',
    'SORT' => 100,
  ),
  301 => 
  array (
    'CONDITION' => '#^/site_gm/crm/activity/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.activity',
    'PATH' => '/site_gm/crm/activity/index.php',
    'SORT' => 100,
  ),
  399 => 
  array (
    'CONDITION' => '#^/site_rj/crm/activity/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.activity',
    'PATH' => '/site_rj/crm/activity/index.php',
    'SORT' => 100,
  ),
  200 => 
  array (
    'CONDITION' => '#^/site_qn/crm/tracking/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_qn/crm/tracking/index.php',
    'SORT' => 100,
  ),
  494 => 
  array (
    'CONDITION' => '#^/site_pq/crm/tracking/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_pq/crm/tracking/index.php',
    'SORT' => 100,
  ),
  321 => 
  array (
    'CONDITION' => '#^/site_rj/services/faq/#',
    'RULE' => '',
    'ID' => 'bitrix:support.faq',
    'PATH' => '/site_rj/services/faq/index.php',
    'SORT' => 100,
  ),
  521 => 
  array (
    'CONDITION' => '#^/extranet/marketplace/#',
    'RULE' => NULL,
    'ID' => 'bitrix:rest.marketplace',
    'PATH' => '/extranet/marketplace/index.php',
    'SORT' => 100,
  ),
  137 => 
  array (
    'CONDITION' => '#^/site_qn/shop/stores/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_qn/shop/stores/index.php',
    'SORT' => 100,
  ),
  295 => 
  array (
    'CONDITION' => '#^/site_gm/crm/webform/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_gm/crm/webform/index.php',
    'SORT' => 100,
  ),
  491 => 
  array (
    'CONDITION' => '#^/site_pq/crm/webform/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_pq/crm/webform/index.php',
    'SORT' => 100,
  ),
  469 => 
  array (
    'CONDITION' => '#^/site_pq/crm/contact/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.contact',
    'PATH' => '/site_pq/crm/contact/index.php',
    'SORT' => 100,
  ),
  241 => 
  array (
    'CONDITION' => '#^/site_gm/marketplace/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.marketplace',
    'PATH' => '/site_gm/marketplace/index.php',
    'SORT' => 100,
  ),
  277 => 
  array (
    'CONDITION' => '#^/site_gm/crm/invoice/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.invoice',
    'PATH' => '/site_gm/crm/invoice/index.php',
    'SORT' => 100,
  ),
  230 => 
  array (
    'CONDITION' => '#^/site_gm/docs/manage/#',
    'RULE' => '',
    'ID' => 'bitrix:disk.common',
    'PATH' => '/site_gm/docs/manage/index.php',
    'SORT' => 100,
  ),
  184 => 
  array (
    'CONDITION' => '#^/site_qn/crm/product/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.product',
    'PATH' => '/site_qn/crm/product/index.php',
    'SORT' => 100,
  ),
  372 => 
  array (
    'CONDITION' => '#^/site_rj/crm/company/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.company',
    'PATH' => '/site_rj/crm/company/index.php',
    'SORT' => 100,
  ),
  393 => 
  array (
    'CONDITION' => '#^/site_rj/crm/webform/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_rj/crm/webform/index.php',
    'SORT' => 100,
  ),
  473 => 
  array (
    'CONDITION' => '#^/site_pq/crm/invoice/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.invoice',
    'PATH' => '/site_pq/crm/invoice/index.php',
    'SORT' => 100,
  ),
  95 => 
  array (
    'CONDITION' => '#^/crm/configs/measure/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.measure',
    'PATH' => '/crm/configs/measure/index.php',
    'SORT' => 100,
  ),
  431 => 
  array (
    'CONDITION' => '#^/site_pq/shop/stores/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_pq/shop/stores/index.php',
    'SORT' => 100,
  ),
  426 => 
  array (
    'CONDITION' => '#^/site_pq/docs/manage/#',
    'RULE' => '',
    'ID' => 'bitrix:disk.common',
    'PATH' => '/site_pq/docs/manage/index.php',
    'SORT' => 100,
  ),
  334 => 
  array (
    'CONDITION' => '#^/site_rj/shop/orders/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order',
    'PATH' => '/site_rj/shop/orders/index.php',
    'SORT' => 100,
  ),
  328 => 
  array (
    'CONDITION' => '#^/site_rj/docs/manage/#',
    'RULE' => '',
    'ID' => 'bitrix:disk.common',
    'PATH' => '/site_rj/docs/manage/index.php',
    'SORT' => 100,
  ),
  333 => 
  array (
    'CONDITION' => '#^/site_rj/shop/stores/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_rj/shop/stores/index.php',
    'SORT' => 100,
  ),
  479 => 
  array (
    'CONDITION' => '#^/site_pq/crm/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.catalog.controller',
    'PATH' => '/site_pq/crm/catalog/index.php',
    'SORT' => 100,
  ),
  143 => 
  array (
    'CONDITION' => '#^/site_qn/marketplace/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.marketplace',
    'PATH' => '/site_qn/marketplace/index.php',
    'SORT' => 100,
  ),
  339 => 
  array (
    'CONDITION' => '#^/site_rj/marketplace/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.marketplace',
    'PATH' => '/site_rj/marketplace/index.php',
    'SORT' => 100,
  ),
  236 => 
  array (
    'CONDITION' => '#^/site_gm/shop/orders/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order',
    'PATH' => '/site_gm/shop/orders/index.php',
    'SORT' => 100,
  ),
  283 => 
  array (
    'CONDITION' => '#^/site_gm/crm/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.catalog.controller',
    'PATH' => '/site_gm/crm/catalog/index.php',
    'SORT' => 100,
  ),
  132 => 
  array (
    'CONDITION' => '#^/site_qn/docs/manage/#',
    'RULE' => '',
    'ID' => 'bitrix:disk.common',
    'PATH' => '/site_qn/docs/manage/index.php',
    'SORT' => 100,
  ),
  179 => 
  array (
    'CONDITION' => '#^/site_qn/crm/invoice/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.invoice',
    'PATH' => '/site_qn/crm/invoice/index.php',
    'SORT' => 100,
  ),
  375 => 
  array (
    'CONDITION' => '#^/site_rj/crm/invoice/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.invoice',
    'PATH' => '/site_rj/crm/invoice/index.php',
    'SORT' => 100,
  ),
  282 => 
  array (
    'CONDITION' => '#^/site_gm/crm/product/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.product',
    'PATH' => '/site_gm/crm/product/index.php',
    'SORT' => 100,
  ),
  437 => 
  array (
    'CONDITION' => '#^/site_pq/marketplace/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.marketplace',
    'PATH' => '/site_pq/marketplace/index.php',
    'SORT' => 100,
  ),
  138 => 
  array (
    'CONDITION' => '#^/site_qn/shop/orders/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order',
    'PATH' => '/site_qn/shop/orders/index.php',
    'SORT' => 100,
  ),
  371 => 
  array (
    'CONDITION' => '#^/site_rj/crm/contact/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.contact',
    'PATH' => '/site_rj/crm/contact/index.php',
    'SORT' => 100,
  ),
  235 => 
  array (
    'CONDITION' => '#^/site_gm/shop/stores/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_gm/shop/stores/index.php',
    'SORT' => 100,
  ),
  380 => 
  array (
    'CONDITION' => '#^/site_rj/crm/product/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.product',
    'PATH' => '/site_rj/crm/product/index.php',
    'SORT' => 100,
  ),
  175 => 
  array (
    'CONDITION' => '#^/site_qn/crm/contact/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.contact',
    'PATH' => '/site_qn/crm/contact/index.php',
    'SORT' => 100,
  ),
  381 => 
  array (
    'CONDITION' => '#^/site_rj/crm/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.catalog.controller',
    'PATH' => '/site_rj/crm/catalog/index.php',
    'SORT' => 100,
  ),
  273 => 
  array (
    'CONDITION' => '#^/site_gm/crm/contact/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.contact',
    'PATH' => '/site_gm/crm/contact/index.php',
    'SORT' => 100,
  ),
  470 => 
  array (
    'CONDITION' => '#^/site_pq/crm/company/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.company',
    'PATH' => '/site_pq/crm/company/index.php',
    'SORT' => 100,
  ),
  197 => 
  array (
    'CONDITION' => '#^/site_qn/crm/webform/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_qn/crm/webform/index.php',
    'SORT' => 100,
  ),
  432 => 
  array (
    'CONDITION' => '#^/site_pq/shop/orders/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order',
    'PATH' => '/site_pq/shop/orders/index.php',
    'SORT' => 100,
  ),
  185 => 
  array (
    'CONDITION' => '#^/site_qn/crm/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.catalog.controller',
    'PATH' => '/site_qn/crm/catalog/index.php',
    'SORT' => 100,
  ),
  478 => 
  array (
    'CONDITION' => '#^/site_pq/crm/product/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.product',
    'PATH' => '/site_pq/crm/product/index.php',
    'SORT' => 100,
  ),
  113 => 
  array (
    'CONDITION' => '#^/marketing/blacklist/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/marketing/blacklist.php',
    'SORT' => 100,
  ),
  274 => 
  array (
    'CONDITION' => '#^/site_gm/crm/company/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.company',
    'PATH' => '/site_gm/crm/company/index.php',
    'SORT' => 100,
  ),
  176 => 
  array (
    'CONDITION' => '#^/site_qn/crm/company/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.company',
    'PATH' => '/site_qn/crm/company/index.php',
    'SORT' => 100,
  ),
  518 => 
  array (
    'CONDITION' => '#^/extranet/workgroups/#',
    'RULE' => NULL,
    'ID' => 'bitrix:socialnetwork_group',
    'PATH' => '/extranet/workgroups/index.php',
    'SORT' => 100,
  ),
  571 => 
  array (
    'CONDITION' => '#^/call/detail/([0-9]+)#',
    'RULE' => 'callId=$1',
    'ID' => 'bitrix:call',
    'PATH' => '/call/index.php',
    'SORT' => 100,
  ),
  166 => 
  array (
    'CONDITION' => '#^/site_qn/conference/#',
    'RULE' => '',
    'ID' => 'bitrix:im.conference.center',
    'PATH' => '/site_qn/conference/index.php',
    'SORT' => 100,
  ),
  417 => 
  array (
    'CONDITION' => '#^/site_pq/workgroups/#',
    'RULE' => '',
    'ID' => 'bitrix:socialnetwork_group',
    'PATH' => '/site_pq/workgroups/index.php',
    'SORT' => 100,
  ),
  155 => 
  array (
    'CONDITION' => '#^/site_qn/shop/buyer/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.buyer',
    'PATH' => '/site_qn/shop/buyer/index.php',
    'SORT' => 100,
  ),
  221 => 
  array (
    'CONDITION' => '#^/site_gm/workgroups/#',
    'RULE' => '',
    'ID' => 'bitrix:socialnetwork_group',
    'PATH' => '/site_gm/workgroups/index.php',
    'SORT' => 100,
  ),
  131 => 
  array (
    'CONDITION' => '#^/site_qn/docs/shared#',
    'RULE' => '',
    'ID' => 'bitrix:disk.common',
    'PATH' => '/site_qn/docs/shared/index.php',
    'SORT' => 100,
  ),
  98 => 
  array (
    'CONDITION' => '#^/crm/configs/preset/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.preset',
    'PATH' => '/crm/configs/preset/index.php',
    'SORT' => 100,
  ),
  394 => 
  array (
    'CONDITION' => '#^/site_rj/crm/button/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_rj/crm/button/index.php',
    'SORT' => 100,
  ),
  264 => 
  array (
    'CONDITION' => '#^/site_gm/conference/#',
    'RULE' => '',
    'ID' => 'bitrix:im.conference.center',
    'PATH' => '/site_gm/conference/index.php',
    'SORT' => 100,
  ),
  460 => 
  array (
    'CONDITION' => '#^/site_pq/conference/#',
    'RULE' => '',
    'ID' => 'bitrix:im.conference.center',
    'PATH' => '/site_pq/conference/index.php',
    'SORT' => 100,
  ),
  123 => 
  array (
    'CONDITION' => '#^/site_qn/workgroups/#',
    'RULE' => '',
    'ID' => 'bitrix:socialnetwork_group',
    'PATH' => '/site_qn/workgroups/index.php',
    'SORT' => 100,
  ),
  198 => 
  array (
    'CONDITION' => '#^/site_qn/crm/button/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_qn/crm/button/index.php',
    'SORT' => 100,
  ),
  112 => 
  array (
    'CONDITION' => '#^/marketing/template/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/marketing/template.php',
    'SORT' => 100,
  ),
  94 => 
  array (
    'CONDITION' => '#^/crm/configs/exch1c/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.exch1c',
    'PATH' => '/crm/configs/exch1c/index.php',
    'SORT' => 100,
  ),
  319 => 
  array (
    'CONDITION' => '#^/site_rj/workgroups/#',
    'RULE' => '',
    'ID' => 'bitrix:socialnetwork_group',
    'PATH' => '/site_rj/workgroups/index.php',
    'SORT' => 100,
  ),
  253 => 
  array (
    'CONDITION' => '#^/site_gm/shop/buyer/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.buyer',
    'PATH' => '/site_gm/shop/buyer/index.php',
    'SORT' => 100,
  ),
  229 => 
  array (
    'CONDITION' => '#^/site_gm/docs/shared#',
    'RULE' => '',
    'ID' => 'bitrix:disk.common',
    'PATH' => '/site_gm/docs/shared/index.php',
    'SORT' => 100,
  ),
  96 => 
  array (
    'CONDITION' => '#^/crm/configs/volume/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.volume',
    'PATH' => '/crm/configs/volume/index.php',
    'SORT' => 100,
  ),
  425 => 
  array (
    'CONDITION' => '#^/site_pq/docs/shared#',
    'RULE' => '',
    'ID' => 'bitrix:disk.common',
    'PATH' => '/site_pq/docs/shared/index.php',
    'SORT' => 100,
  ),
  492 => 
  array (
    'CONDITION' => '#^/site_pq/crm/button/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_pq/crm/button/index.php',
    'SORT' => 100,
  ),
  362 => 
  array (
    'CONDITION' => '#^/site_rj/conference/#',
    'RULE' => '',
    'ID' => 'bitrix:im.conference.center',
    'PATH' => '/site_rj/conference/index.php',
    'SORT' => 100,
  ),
  327 => 
  array (
    'CONDITION' => '#^/site_rj/docs/shared#',
    'RULE' => '',
    'ID' => 'bitrix:disk.common',
    'PATH' => '/site_rj/docs/shared/index.php',
    'SORT' => 100,
  ),
  351 => 
  array (
    'CONDITION' => '#^/site_rj/shop/buyer/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.buyer',
    'PATH' => '/site_rj/shop/buyer/index.php',
    'SORT' => 100,
  ),
  296 => 
  array (
    'CONDITION' => '#^/site_gm/crm/button/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/site_gm/crm/button/index.php',
    'SORT' => 100,
  ),
  82 => 
  array (
    'CONDITION' => '#^/crm/configs/fields/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.fields',
    'PATH' => '/crm/configs/fields/index.php',
    'SORT' => 100,
  ),
  92 => 
  array (
    'CONDITION' => '#^/crm/reports/report/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.report',
    'PATH' => '/crm/reports/report/index.php',
    'SORT' => 100,
  ),
  449 => 
  array (
    'CONDITION' => '#^/site_pq/shop/buyer/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.buyer',
    'PATH' => '/site_pq/shop/buyer/index.php',
    'SORT' => 100,
  ),
  549 => 
  array (
    'CONDITION' => '#^/helpdesk/equipment/#',
    'RULE' => '',
    'ID' => 'darneo:equipment',
    'PATH' => '/helpdesk/equipment/index.php',
    'SORT' => 100,
  ),
  579 => 
  array (
    'CONDITION' => '#^/bi/unused_elements/#',
    'RULE' => '',
    'ID' => 'bitrix:biconnector.apachesuperset.workspace_analytic.controller',
    'PATH' => '/bi/unused_elements/index.php',
    'SORT' => 100,
  ),
  354 => 
  array (
    'CONDITION' => '#^/site_rj/knowledge/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_rj/knowledge/index.php',
    'SORT' => 100,
  ),
  85 => 
  array (
    'CONDITION' => '#^/crm/configs/perms/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.perms',
    'PATH' => '/crm/configs/perms/index.php',
    'SORT' => 100,
  ),
  42 => 
  array (
    'CONDITION' => '#^/bizproc/processes/#',
    'RULE' => '',
    'ID' => 'bitrix:lists',
    'PATH' => '/bizproc/processes/index.php',
    'SORT' => 100,
  ),
  326 => 
  array (
    'CONDITION' => '#^/site_rj/docs/sale/#',
    'RULE' => '',
    'ID' => 'bitrix:disk.common',
    'PATH' => '/site_rj/docs/sale/index.php',
    'SORT' => 100,
  ),
  178 => 
  array (
    'CONDITION' => '#^/site_qn/crm/quote/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.quote',
    'PATH' => '/site_qn/crm/quote/index.php',
    'SORT' => 100,
  ),
  276 => 
  array (
    'CONDITION' => '#^/site_gm/crm/quote/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.quote',
    'PATH' => '/site_gm/crm/quote/index.php',
    'SORT' => 100,
  ),
  158 => 
  array (
    'CONDITION' => '#^/site_qn/knowledge/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_qn/knowledge/index.php',
    'SORT' => 100,
  ),
  424 => 
  array (
    'CONDITION' => '#^/site_pq/docs/sale/#',
    'RULE' => '',
    'ID' => 'bitrix:disk.common',
    'PATH' => '/site_pq/docs/sale/index.php',
    'SORT' => 100,
  ),
  55 => 
  array (
    'CONDITION' => '#^/timeman/schedules/#',
    'RULE' => '',
    'ID' => 'bitrix:timeman.schedules',
    'PATH' => '/timeman/schedules.php',
    'SORT' => 100,
  ),
  452 => 
  array (
    'CONDITION' => '#^/site_pq/knowledge/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_pq/knowledge/index.php',
    'SORT' => 100,
  ),
  228 => 
  array (
    'CONDITION' => '#^/site_gm/docs/sale/#',
    'RULE' => '',
    'ID' => 'bitrix:disk.common',
    'PATH' => '/site_gm/docs/sale/index.php',
    'SORT' => 100,
  ),
  23 => 
  array (
    'CONDITION' => '#^/marketplace/local/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.marketplace.localapp',
    'PATH' => '/marketplace/local/index.php',
    'SORT' => 100,
  ),
  111 => 
  array (
    'CONDITION' => '#^/marketing/segment/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/marketing/segment.php',
    'SORT' => 100,
  ),
  472 => 
  array (
    'CONDITION' => '#^/site_pq/crm/quote/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.quote',
    'PATH' => '/site_pq/crm/quote/index.php',
    'SORT' => 100,
  ),
  12 => 
  array (
    'CONDITION' => '#^/online/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  256 => 
  array (
    'CONDITION' => '#^/site_gm/knowledge/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_gm/knowledge/index.php',
    'SORT' => 100,
  ),
  114 => 
  array (
    'CONDITION' => '#^/marketing/contact/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/marketing/contact.php',
    'SORT' => 100,
  ),
  130 => 
  array (
    'CONDITION' => '#^/site_qn/docs/sale/#',
    'RULE' => '',
    'ID' => 'bitrix:disk.common',
    'PATH' => '/site_qn/docs/sale/index.php',
    'SORT' => 100,
  ),
  374 => 
  array (
    'CONDITION' => '#^/site_rj/crm/quote/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.quote',
    'PATH' => '/site_rj/crm/quote/index.php',
    'SORT' => 100,
  ),
  546 => 
  array (
    'CONDITION' => '#^/helpdesk/settings/#',
    'RULE' => '',
    'ID' => 'darneo:settings',
    'PATH' => '/helpdesk/settings/index.php',
    'SORT' => 100,
  ),
  548 => 
  array (
    'CONDITION' => '#^/helpdesk/contract/#',
    'RULE' => '',
    'ID' => 'darneo:contract',
    'PATH' => '/helpdesk/contract/index.php',
    'SORT' => 100,
  ),
  459 => 
  array (
    'CONDITION' => '#^/site_pq/crm/type/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.router',
    'PATH' => '/site_pq/crm/type/index.php',
    'SORT' => 100,
  ),
  263 => 
  array (
    'CONDITION' => '#^/site_gm/crm/type/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.router',
    'PATH' => '/site_gm/crm/type/index.php',
    'SORT' => 100,
  ),
  227 => 
  array (
    'CONDITION' => '#^/site_gm//docs/all#',
    'RULE' => '',
    'ID' => 'bitrix:disk.aggregator',
    'PATH' => '/site_gm/docs/index.php',
    'SORT' => 100,
  ),
  370 => 
  array (
    'CONDITION' => '#^/site_rj/crm/lead/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.lead',
    'PATH' => '/site_rj/crm/lead/index.php',
    'SORT' => 100,
  ),
  161 => 
  array (
    'CONDITION' => '#^/site_qn/kb/group/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_qn/kb/group/index.php',
    'SORT' => 100,
  ),
  129 => 
  array (
    'CONDITION' => '#^/site_qn//docs/all#',
    'RULE' => '',
    'ID' => 'bitrix:disk.aggregator',
    'PATH' => '/site_qn/docs/index.php',
    'SORT' => 100,
  ),
  53 => 
  array (
    'CONDITION' => '#^/timeman/worktime/#',
    'RULE' => '',
    'ID' => 'bitrix:timeman.worktime',
    'PATH' => '/timeman/worktime.php',
    'SORT' => 100,
  ),
  226 => 
  array (
    'CONDITION' => '#^/site_gm/docs/pub/#',
    'RULE' => '',
    'ID' => 'bitrix:disk.external.link',
    'PATH' => '/site_gm/docs/pub/extlinks.php',
    'SORT' => 100,
  ),
  25 => 
  array (
    'CONDITION' => '#^/marketplace/hook/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.hook',
    'PATH' => '/marketplace/hook/index.php',
    'SORT' => 100,
  ),
  259 => 
  array (
    'CONDITION' => '#^/site_gm/kb/group/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_gm/kb/group/index.php',
    'SORT' => 100,
  ),
  57 => 
  array (
    'CONDITION' => '#^/shop/buyer_group/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.buyer_group',
    'PATH' => '/shop/buyer_group/index.php',
    'SORT' => 100,
  ),
  116 => 
  array (
    'CONDITION' => '#^/marketing/toloka/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/marketing/toloka.php',
    'SORT' => 100,
  ),
  468 => 
  array (
    'CONDITION' => '#^/site_pq/crm/lead/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.lead',
    'PATH' => '/site_pq/crm/lead/index.php',
    'SORT' => 100,
  ),
  373 => 
  array (
    'CONDITION' => '#^/site_rj/crm/deal/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.deal',
    'PATH' => '/site_rj/crm/deal/index.php',
    'SORT' => 100,
  ),
  30 => 
  array (
    'CONDITION' => '#^/company/personal/#',
    'RULE' => '',
    'ID' => 'bitrix:socialnetwork_user',
    'PATH' => '/company/personal.php',
    'SORT' => 100,
  ),
  109 => 
  array (
    'CONDITION' => '#^/marketing/letter/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/marketing/letter.php',
    'SORT' => 100,
  ),
  174 => 
  array (
    'CONDITION' => '#^/site_qn/crm/lead/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.lead',
    'PATH' => '/site_qn/crm/lead/index.php',
    'SORT' => 100,
  ),
  165 => 
  array (
    'CONDITION' => '#^/site_qn/crm/type/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.router',
    'PATH' => '/site_qn/crm/type/index.php',
    'SORT' => 100,
  ),
  357 => 
  array (
    'CONDITION' => '#^/site_rj/kb/group/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_rj/kb/group/index.php',
    'SORT' => 100,
  ),
  423 => 
  array (
    'CONDITION' => '#^/site_pq//docs/all#',
    'RULE' => '',
    'ID' => 'bitrix:disk.aggregator',
    'PATH' => '/site_pq/docs/index.php',
    'SORT' => 100,
  ),
  272 => 
  array (
    'CONDITION' => '#^/site_gm/crm/lead/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.lead',
    'PATH' => '/site_gm/crm/lead/index.php',
    'SORT' => 100,
  ),
  422 => 
  array (
    'CONDITION' => '#^/site_pq/docs/pub/#',
    'RULE' => '',
    'ID' => 'bitrix:disk.external.link',
    'PATH' => '/site_pq/docs/pub/extlinks.php',
    'SORT' => 100,
  ),
  54 => 
  array (
    'CONDITION' => '#^/timeman/settings/#',
    'RULE' => '',
    'ID' => 'bitrix:timeman.settings',
    'PATH' => '/timeman/settings.php',
    'SORT' => 100,
  ),
  0 => 
  array (
    'CONDITION' => '#^/stssync/calendar/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/calendar/index.php',
    'SORT' => 100,
  ),
  471 => 
  array (
    'CONDITION' => '#^/site_pq/crm/deal/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.deal',
    'PATH' => '/site_pq/crm/deal/index.php',
    'SORT' => 100,
  ),
  324 => 
  array (
    'CONDITION' => '#^/site_rj/docs/pub/#',
    'RULE' => '',
    'ID' => 'bitrix:disk.external.link',
    'PATH' => '/site_rj/docs/pub/extlinks.php',
    'SORT' => 100,
  ),
  361 => 
  array (
    'CONDITION' => '#^/site_rj/crm/type/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.router',
    'PATH' => '/site_rj/crm/type/index.php',
    'SORT' => 100,
  ),
  325 => 
  array (
    'CONDITION' => '#^/site_rj//docs/all#',
    'RULE' => '',
    'ID' => 'bitrix:disk.aggregator',
    'PATH' => '/site_rj/docs/index.php',
    'SORT' => 100,
  ),
  13 => 
  array (
    'CONDITION' => '#^/stssync/contacts/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/contacts/index.php',
    'SORT' => 100,
  ),
  63 => 
  array (
    'CONDITION' => '#^/mobile/knowledge/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/mobile/knowledge/index.php',
    'SORT' => 100,
  ),
  128 => 
  array (
    'CONDITION' => '#^/site_qn/docs/pub/#',
    'RULE' => '',
    'ID' => 'bitrix:disk.external.link',
    'PATH' => '/site_qn/docs/pub/extlinks.php',
    'SORT' => 100,
  ),
  455 => 
  array (
    'CONDITION' => '#^/site_pq/kb/group/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_pq/kb/group/index.php',
    'SORT' => 100,
  ),
  275 => 
  array (
    'CONDITION' => '#^/site_gm/crm/deal/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.deal',
    'PATH' => '/site_gm/crm/deal/index.php',
    'SORT' => 100,
  ),
  177 => 
  array (
    'CONDITION' => '#^/site_qn/crm/deal/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.deal',
    'PATH' => '/site_qn/crm/deal/index.php',
    'SORT' => 100,
  ),
  540 => 
  array (
    'CONDITION' => '#^/helpdesk/tickets/#',
    'RULE' => '',
    'ID' => 'darneo:tickets',
    'PATH' => '/helpdesk/tickets/index.php',
    'SORT' => 100,
  ),
  541 => 
  array (
    'CONDITION' => '#^/helpdesk/company/#',
    'RULE' => '',
    'ID' => 'darneo:company',
    'PATH' => '/helpdesk/company/index.php',
    'SORT' => 100,
  ),
  547 => 
  array (
    'CONDITION' => '#^/helpdesk/history/#',
    'RULE' => '',
    'ID' => 'darneo:history',
    'PATH' => '/helpdesk/history/index.php',
    'SORT' => 100,
  ),
  557 => 
  array (
    'CONDITION' => '#^/site_gm/pub/site/#',
    'RULE' => NULL,
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_gm/pub/site/index.php',
    'SORT' => 100,
  ),
  558 => 
  array (
    'CONDITION' => '#^/site_rj/pub/site/#',
    'RULE' => NULL,
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/site_rj/pub/site/index.php',
    'SORT' => 100,
  ),
  108 => 
  array (
    'CONDITION' => '#^/timeman/meeting/#',
    'RULE' => '',
    'ID' => 'bitrix:meetings',
    'PATH' => '/timeman/meeting/index.php',
    'SORT' => 100,
  ),
  89 => 
  array (
    'CONDITION' => '#^/crm/configs/tax/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.tax',
    'PATH' => '/crm/configs/tax/index.php',
    'SORT' => 100,
  ),
  29 => 
  array (
    'CONDITION' => '#^/company/gallery/#',
    'RULE' => '',
    'ID' => 'bitrix:photogallery_user',
    'PATH' => '/company/gallery/index.php',
    'SORT' => 100,
  ),
  60 => 
  array (
    'CONDITION' => '#^/knowledge/group/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/knowledge/group/index.php',
    'SORT' => 100,
  ),
  24 => 
  array (
    'CONDITION' => '#^/marketplace/app/#',
    'RULE' => '',
    'ID' => 'bitrix:app.layout',
    'PATH' => '/marketplace/app/index.php',
    'SORT' => 100,
  ),
  537 => 
  array (
    'CONDITION' => '#^/automation/type/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.router',
    'PATH' => '/automation/type/index.php',
    'SORT' => 100,
  ),
  542 => 
  array (
    'CONDITION' => '#^/helpdesk/client/#',
    'RULE' => '',
    'ID' => 'darneo:client',
    'PATH' => '/helpdesk/client/index.php',
    'SORT' => 100,
  ),
  544 => 
  array (
    'CONDITION' => '#^/helpdesk/report/#',
    'RULE' => '',
    'ID' => 'darneo:report',
    'PATH' => '/helpdesk/report/index.php',
    'SORT' => 100,
  ),
  545 => 
  array (
    'CONDITION' => '#^/helpdesk/object/#',
    'RULE' => '',
    'ID' => 'darneo:object',
    'PATH' => '/helpdesk/object/index.php',
    'SORT' => 100,
  ),
  550 => 
  array (
    'CONDITION' => '#^/helpdesk/access/#',
    'RULE' => '',
    'ID' => 'darneo:access',
    'PATH' => '/helpdesk/access/index.php',
    'SORT' => 100,
  ),
  344 => 
  array (
    'CONDITION' => '#^/site_rj/devops/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.devops',
    'PATH' => '/site_rj/devops/index.php',
    'SORT' => 100,
  ),
  442 => 
  array (
    'CONDITION' => '#^/site_pq/devops/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.devops',
    'PATH' => '/site_pq/devops/index.php',
    'SORT' => 100,
  ),
  246 => 
  array (
    'CONDITION' => '#^/site_gm/devops/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.devops',
    'PATH' => '/site_gm/devops/index.php',
    'SORT' => 100,
  ),
  84 => 
  array (
    'CONDITION' => '#^/crm/configs/bp/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.bp',
    'PATH' => '/crm/configs/bp/index.php',
    'SORT' => 100,
  ),
  205 => 
  array (
    'CONDITION' => '#^/site_qn/crm/ml/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.ml',
    'PATH' => '/site_qn/crm/ml/index.php',
    'SORT' => 100,
  ),
  148 => 
  array (
    'CONDITION' => '#^/site_qn/devops/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.devops',
    'PATH' => '/site_qn/devops/index.php',
    'SORT' => 100,
  ),
  72 => 
  array (
    'CONDITION' => '#^/calendar/rooms/#',
    'RULE' => '',
    'ID' => 'bitrix:calender',
    'PATH' => '/calendar/rooms.php',
    'SORT' => 100,
  ),
  499 => 
  array (
    'CONDITION' => '#^/site_pq/crm/ml/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.ml',
    'PATH' => '/site_pq/crm/ml/index.php',
    'SORT' => 100,
  ),
  56 => 
  array (
    'CONDITION' => '#^/shop/orderform/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.matcher',
    'PATH' => '/shop/orderform/index.php',
    'SORT' => 100,
  ),
  73 => 
  array (
    'CONDITION' => '#^/shop/documents/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store.document',
    'PATH' => '/shop/documents/index.php',
    'SORT' => 100,
  ),
  401 => 
  array (
    'CONDITION' => '#^/site_rj/crm/ml/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.ml',
    'PATH' => '/site_rj/crm/ml/index.php',
    'SORT' => 100,
  ),
  303 => 
  array (
    'CONDITION' => '#^/site_gm/crm/ml/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.ml',
    'PATH' => '/site_gm/crm/ml/index.php',
    'SORT' => 100,
  ),
  33 => 
  array (
    'CONDITION' => '#^/services/lists/#',
    'RULE' => '',
    'ID' => 'bitrix:lists',
    'PATH' => '/services/lists/index.php',
    'SORT' => 100,
  ),
  91 => 
  array (
    'CONDITION' => '#^/crm/configs/ps/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.config.ps',
    'PATH' => '/crm/configs/ps/index.php',
    'SORT' => 100,
  ),
  532 => 
  array (
    'CONDITION' => '#^/agent_contract/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.agent.contract.controller',
    'PATH' => '/agent_contract/index.php',
    'SORT' => 100,
  ),
  543 => 
  array (
    'CONDITION' => '#^/helpdesk/staff/#',
    'RULE' => '',
    'ID' => 'darneo:staff',
    'PATH' => '/helpdesk/staff/index.php',
    'SORT' => 100,
  ),
  35 => 
  array (
    'CONDITION' => '#^/services/idea/#',
    'RULE' => '',
    'ID' => 'bitrix:idea',
    'PATH' => '/services/idea/index.php',
    'SORT' => 100,
  ),
  28 => 
  array (
    'CONDITION' => '#^/stssync/tasks/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/tasks/index.php',
    'SORT' => 100,
  ),
  332 => 
  array (
    'CONDITION' => '#^/site_rj/sites/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_rj/sites/index.php',
    'SORT' => 100,
  ),
  110 => 
  array (
    'CONDITION' => '#^/marketing/ads/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/marketing/ads.php',
    'SORT' => 100,
  ),
  234 => 
  array (
    'CONDITION' => '#^/site_gm/sites/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_gm/sites/index.php',
    'SORT' => 100,
  ),
  43 => 
  array (
    'CONDITION' => '#^/shop/settings/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.admin.page.controller',
    'PATH' => '/shop/settings/index.php',
    'SORT' => 100,
  ),
  136 => 
  array (
    'CONDITION' => '#^/site_qn/sites/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_qn/sites/index.php',
    'SORT' => 100,
  ),
  430 => 
  array (
    'CONDITION' => '#^/site_pq/sites/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_pq/sites/index.php',
    'SORT' => 100,
  ),
  31 => 
  array (
    'CONDITION' => '#^/about/gallery/#',
    'RULE' => '',
    'ID' => 'bitrix:photogallery',
    'PATH' => '/about/gallery/index.php',
    'SORT' => 100,
  ),
  526 => 
  array (
    'CONDITION' => '#^/shop/terminal/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.terminal.payment.controller',
    'PATH' => '/terminal/index.php',
    'SORT' => 100,
  ),
  538 => 
  array (
    'CONDITION' => '#^/calendar/open/#',
    'RULE' => '',
    'ID' => 'bitrix:calendar.open-events',
    'PATH' => '/calendar/open_events.php',
    'SORT' => 100,
  ),
  569 => 
  array (
    'CONDITION' => '#^/bi/statistics/#',
    'RULE' => '',
    'ID' => 'bitrix:biconnector.apachesuperset.workspace_analytic.controller',
    'PATH' => '/bi/statistics/index.php',
    'SORT' => 100,
  ),
  105 => 
  array (
    'CONDITION' => '#^/crm/activity/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.activity',
    'PATH' => '/crm/activity/index.php',
    'SORT' => 100,
  ),
  363 => 
  array (
    'CONDITION' => '#^/site_rj/page/#',
    'RULE' => '',
    'ID' => 'bitrix:intranet.customsection',
    'PATH' => '/site_rj/page/index.php',
    'SORT' => 100,
  ),
  141 => 
  array (
    'CONDITION' => '#^/site_qn/onec/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.1c.start',
    'PATH' => '/site_qn/onec/index.php',
    'SORT' => 100,
  ),
  239 => 
  array (
    'CONDITION' => '#^/site_gm/onec/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.1c.start',
    'PATH' => '/site_gm/onec/index.php',
    'SORT' => 100,
  ),
  247 => 
  array (
    'CONDITION' => '#^/site_gm/mail/#',
    'RULE' => '',
    'ID' => 'bitrix:mail.client',
    'PATH' => '/site_gm/mail/index.php',
    'SORT' => 100,
  ),
  34 => 
  array (
    'CONDITION' => '#^/services/faq/#',
    'RULE' => '',
    'ID' => 'bitrix:support.faq',
    'PATH' => '/services/faq/index.php',
    'SORT' => 100,
  ),
  435 => 
  array (
    'CONDITION' => '#^/site_pq/onec/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.1c.start',
    'PATH' => '/site_pq/onec/index.php',
    'SORT' => 100,
  ),
  167 => 
  array (
    'CONDITION' => '#^/site_qn/page/#',
    'RULE' => '',
    'ID' => 'bitrix:intranet.customsection',
    'PATH' => '/site_qn/page/index.php',
    'SORT' => 100,
  ),
  75 => 
  array (
    'CONDITION' => '#^/shop/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.productcard.controller',
    'PATH' => '//shop/catalog/index.php',
    'SORT' => 100,
  ),
  14 => 
  array (
    'CONDITION' => '#^/mobile/webdav#',
    'RULE' => '',
    'ID' => 'bitrix:mobile.webdav.file.list',
    'PATH' => '/mobile/webdav/index.php',
    'SORT' => 100,
  ),
  461 => 
  array (
    'CONDITION' => '#^/site_pq/page/#',
    'RULE' => '',
    'ID' => 'bitrix:intranet.customsection',
    'PATH' => '/site_pq/page/index.php',
    'SORT' => 100,
  ),
  443 => 
  array (
    'CONDITION' => '#^/site_pq/mail/#',
    'RULE' => '',
    'ID' => 'bitrix:mail.client',
    'PATH' => '/site_pq/mail/index.php',
    'SORT' => 100,
  ),
  115 => 
  array (
    'CONDITION' => '#^/marketing/rc/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/marketing/rc.php',
    'SORT' => 100,
  ),
  345 => 
  array (
    'CONDITION' => '#^/site_rj/mail/#',
    'RULE' => '',
    'ID' => 'bitrix:mail.client',
    'PATH' => '/site_rj/mail/index.php',
    'SORT' => 100,
  ),
  265 => 
  array (
    'CONDITION' => '#^/site_gm/page/#',
    'RULE' => '',
    'ID' => 'bitrix:intranet.customsection',
    'PATH' => '/site_gm/page/index.php',
    'SORT' => 100,
  ),
  337 => 
  array (
    'CONDITION' => '#^/site_rj/onec/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.1c.start',
    'PATH' => '/site_rj/onec/index.php',
    'SORT' => 100,
  ),
  102 => 
  array (
    'CONDITION' => '#^/crm/tracking/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/crm/tracking/index.php',
    'SORT' => 100,
  ),
  149 => 
  array (
    'CONDITION' => '#^/site_qn/mail/#',
    'RULE' => '',
    'ID' => 'bitrix:mail.client',
    'PATH' => '/site_qn/mail/index.php',
    'SORT' => 100,
  ),
  527 => 
  array (
    'CONDITION' => '#^/crm/terminal/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.terminal.payment.controller',
    'PATH' => '/terminal/index.php',
    'SORT' => 100,
  ),
  535 => 
  array (
    'CONDITION' => '#^/bi/dashboard/#',
    'RULE' => '',
    'ID' => 'bitrix:biconnector.apachesuperset.dashboard.controller',
    'PATH' => '/bi/dashboard/index.php',
    'SORT' => 100,
  ),
  589 => 
  array (
    'CONDITION' => '#^/desktop/menu/#',
    'RULE' => '',
    'ID' => 'bitrix:intranet.menu',
    'PATH' => '/desktop_menu/index.php',
    'SORT' => 100,
  ),
  359 => 
  array (
    'CONDITION' => '#^/site_rj/rpa/#',
    'RULE' => '',
    'ID' => 'bitrix:rpa.router',
    'PATH' => '/site_rj/rpa/index.php',
    'SORT' => 100,
  ),
  81 => 
  array (
    'CONDITION' => '#^/crm/invoice/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.invoice',
    'PATH' => '/crm/invoice/index.php',
    'SORT' => 100,
  ),
  45 => 
  array (
    'CONDITION' => '#^/shop/stores/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/shop/stores/index.php',
    'SORT' => 100,
  ),
  78 => 
  array (
    'CONDITION' => '#^/crm/company/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.company',
    'PATH' => '/crm/company/index.php',
    'SORT' => 100,
  ),
  41 => 
  array (
    'CONDITION' => '#^/docs/manage/#',
    'RULE' => '',
    'ID' => 'bitrix:disk.common',
    'PATH' => '/docs/manage/index.php',
    'SORT' => 100,
  ),
  22 => 
  array (
    'CONDITION' => '#^/marketplace/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.marketplace',
    'PATH' => '/marketplace/index.php',
    'SORT' => 100,
  ),
  99 => 
  array (
    'CONDITION' => '#^/crm/webform/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/crm/webform/index.php',
    'SORT' => 100,
  ),
  457 => 
  array (
    'CONDITION' => '#^/site_pq/rpa/#',
    'RULE' => '',
    'ID' => 'bitrix:rpa.router',
    'PATH' => '/site_pq/rpa/index.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/\\.well-known#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/bitrix/groupdav.php',
    'SORT' => 100,
  ),
  87 => 
  array (
    'CONDITION' => '#^/crm/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.catalog.controller',
    'PATH' => '/crm/catalog/index.php',
    'SORT' => 100,
  ),
  46 => 
  array (
    'CONDITION' => '#^/shop/orders/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order',
    'PATH' => '/shop/orders/index.php',
    'SORT' => 100,
  ),
  261 => 
  array (
    'CONDITION' => '#^/site_gm/rpa/#',
    'RULE' => '',
    'ID' => 'bitrix:rpa.router',
    'PATH' => '/site_gm/rpa/index.php',
    'SORT' => 100,
  ),
  163 => 
  array (
    'CONDITION' => '#^/site_qn/rpa/#',
    'RULE' => '',
    'ID' => 'bitrix:rpa.router',
    'PATH' => '/site_qn/rpa/index.php',
    'SORT' => 100,
  ),
  77 => 
  array (
    'CONDITION' => '#^/crm/contact/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.contact',
    'PATH' => '/crm/contact/index.php',
    'SORT' => 100,
  ),
  86 => 
  array (
    'CONDITION' => '#^/crm/product/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.product',
    'PATH' => '/crm/product/index.php',
    'SORT' => 100,
  ),
  539 => 
  array (
    'CONDITION' => '#^/desktop/menu#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/desktop_menu/index.php',
    'SORT' => 100,
  ),
  100 => 
  array (
    'CONDITION' => '#^/crm/button/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/crm/button/index.php',
    'SORT' => 100,
  ),
  260 => 
  array (
    'CONDITION' => '#^/site_gm/kb/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_gm/kb/index.php',
    'SORT' => 100,
  ),
  358 => 
  array (
    'CONDITION' => '#^/site_rj/kb/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_rj/kb/index.php',
    'SORT' => 100,
  ),
  162 => 
  array (
    'CONDITION' => '#^/site_qn/kb/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_qn/kb/index.php',
    'SORT' => 100,
  ),
  58 => 
  array (
    'CONDITION' => '#^/shop/buyer/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.order.buyer',
    'PATH' => '/shop/buyer/index.php',
    'SORT' => 100,
  ),
  69 => 
  array (
    'CONDITION' => '#^/conference/#',
    'RULE' => '',
    'ID' => 'bitrix:im.conference.center',
    'PATH' => '/conference/index.php',
    'SORT' => 100,
  ),
  32 => 
  array (
    'CONDITION' => '#^/workgroups/#',
    'RULE' => '',
    'ID' => 'bitrix:socialnetwork_group',
    'PATH' => '/workgroups/index.php',
    'SORT' => 100,
  ),
  40 => 
  array (
    'CONDITION' => '#^/docs/shared#',
    'RULE' => '',
    'ID' => 'bitrix:disk.common',
    'PATH' => '/docs/shared/index.php',
    'SORT' => 100,
  ),
  456 => 
  array (
    'CONDITION' => '#^/site_pq/kb/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/site_pq/kb/index.php',
    'SORT' => 100,
  ),
  61 => 
  array (
    'CONDITION' => '#^/knowledge/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/knowledge/index.php',
    'SORT' => 100,
  ),
  80 => 
  array (
    'CONDITION' => '#^/crm/quote/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.quote',
    'PATH' => '/crm/quote/index.php',
    'SORT' => 100,
  ),
  39 => 
  array (
    'CONDITION' => '#^/docs/sale/#',
    'RULE' => '',
    'ID' => 'bitrix:disk.common',
    'PATH' => '/docs/sale/index.php',
    'SORT' => 100,
  ),
  568 => 
  array (
    'CONDITION' => '#^/bi/source/#',
    'RULE' => '',
    'ID' => 'bitrix:biconnector.apachesuperset.workspace_analytic.controller',
    'PATH' => '/bi/source/index.php',
    'SORT' => 100,
  ),
  79 => 
  array (
    'CONDITION' => '#^/crm/deal/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.deal',
    'PATH' => '/crm/deal/index.php',
    'SORT' => 100,
  ),
  37 => 
  array (
    'CONDITION' => '#^/docs/pub/#',
    'RULE' => '',
    'ID' => 'bitrix:disk.external.link',
    'PATH' => '/docs/pub/extlinks.php',
    'SORT' => 100,
  ),
  76 => 
  array (
    'CONDITION' => '#^/crm/lead/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.lead',
    'PATH' => '/crm/lead/index.php',
    'SORT' => 100,
  ),
  68 => 
  array (
    'CONDITION' => '#^/crm/type/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.router',
    'PATH' => '/crm/type/index.php',
    'SORT' => 100,
  ),
  38 => 
  array (
    'CONDITION' => '#^//docs/all#',
    'RULE' => '',
    'ID' => 'bitrix:disk.aggregator',
    'PATH' => '/docs/index.php',
    'SORT' => 100,
  ),
  64 => 
  array (
    'CONDITION' => '#^/kb/group/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/kb/group/index.php',
    'SORT' => 100,
  ),
  517 => 
  array (
    'CONDITION' => '#^/pub/site/#',
    'RULE' => NULL,
    'ID' => 'bitrix:landing.pub',
    'PATH' => '/pub/site/index.php',
    'SORT' => 100,
  ),
  525 => 
  array (
    'CONDITION' => '#^/terminal/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.terminal.payment.controller',
    'PATH' => '/terminal/index.php',
    'SORT' => 100,
  ),
  583 => 
  array (
    'CONDITION' => '#^vibe/edit/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => 'vibe/edit/index.php',
    'SORT' => 100,
  ),
  561 => 
  array (
    'CONDITION' => '#^/booking/#',
    'RULE' => '',
    'ID' => 'bitrix:booking',
    'PATH' => '/booking/index.php',
    'SORT' => 100,
  ),
  51 => 
  array (
    'CONDITION' => '#^/devops/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.devops',
    'PATH' => '/devops/index.php',
    'SORT' => 100,
  ),
  524 => 
  array (
    'CONDITION' => '#^/market/#',
    'RULE' => '',
    'ID' => 'bitrix:market',
    'PATH' => '/market/index.php',
    'SORT' => 100,
  ),
  534 => 
  array (
    'CONDITION' => '#^/spaces/#',
    'RULE' => '',
    'ID' => 'bitrix:socialnetwork.spaces',
    'PATH' => '/spaces/index.php',
    'SORT' => 100,
  ),
  44 => 
  array (
    'CONDITION' => '#^/sites/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/sites/index.php',
    'SORT' => 100,
  ),
  21 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
  49 => 
  array (
    'CONDITION' => '#^/onec/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.1c.start',
    'PATH' => '/onec/index.php',
    'SORT' => 100,
  ),
  52 => 
  array (
    'CONDITION' => '#^/mail/#',
    'RULE' => '',
    'ID' => 'bitrix:mail.client',
    'PATH' => '/mail/index.php',
    'SORT' => 100,
  ),
  70 => 
  array (
    'CONDITION' => '#^/page/#',
    'RULE' => '',
    'ID' => 'bitrix:intranet.customsection',
    'PATH' => '/page/index.php',
    'SORT' => 100,
  ),
  533 => 
  array (
    'CONDITION' => '#^/sign/#',
    'RULE' => '',
    'ID' => 'bitrix:sign.start',
    'PATH' => '/sign/index.php',
    'SORT' => 100,
  ),
  66 => 
  array (
    'CONDITION' => '#^/rpa/#',
    'RULE' => '',
    'ID' => 'bitrix:rpa.router',
    'PATH' => '/rpa/index.php',
    'SORT' => 100,
  ),
  560 => 
  array (
    'CONDITION' => '#^/crm/#',
    'RULE' => '',
    'ID' => 'bitrix:crm.router',
    'PATH' => '/crm/index.php',
    'SORT' => 100,
  ),
  65 => 
  array (
    'CONDITION' => '#^/kb/#',
    'RULE' => '',
    'ID' => 'bitrix:landing.start',
    'PATH' => '/kb/index.php',
    'SORT' => 100,
  ),
  559 => 
  array (
    'CONDITION' => '#^/hr/#',
    'RULE' => '',
    'ID' => 'bitrix:humanresources.start',
    'PATH' => '/hr/index.php',
    'SORT' => 100,
  ),
);
