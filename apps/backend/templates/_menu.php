<ul id="main-links"><?php
  echo
    content_tag('li',
      link_to('Transaktionen', 'deal', array('title' => 'Transaktionen')),
      ($sf_request->getModule() == 'deal' && $sf_request->getAction() == 'list') ? array('class' => 'selected') : array(), true
    );

  echo
    content_tag('li',
      link_to('Empfehlungen', 'invitation', array('title' => 'Einladungen/Empfehlungen')),
      ($sf_request->getModule() == 'invitation' && $sf_request->getAction() == 'list') ? array('class' => 'selected') : array(), true
    );

  echo
    content_tag('li',
      link_to('Sponsorings', 'sponsoring', array('title' => 'Sponsorings')),
      ($sf_request->getModule() == 'sponsoring' && $sf_request->getAction() == 'list') ? array('class' => 'selected') : array(), true
    );

  echo
    content_tag('li',
      link_to('Sponsoring Plätze', 'sponsoring_place', array('title' => 'Sponsoring Plätze')),
      ($sf_request->getModule() == 'sponsoring_place' && $sf_request->getAction() == 'list') ? array('class' => 'selected') : array(), true
    );

  echo
    content_tag('li',
      link_to('User', 'user', array('title' => 'User')),
      ($sf_request->getModule() == 'user' && $sf_request->getAction() == 'list') ? array('class' => 'selected') : array(), true
    );

  echo
    content_tag('li',
      link_to('Video', 'video', array('title' => 'Video')),
      ($sf_request->getModule() == 'video') ? array('class' => 'selected') : array(),
      true
    );

   echo
    content_tag('li',
      link_to('Domains', 'domain/index', array('title' => 'Domains verwalten')),
      ($sf_request->getModule() == 'domain') ? array('class' => 'selected') : array(),
      true
    );

  echo
    content_tag('li',
      link_to('WSpy', 'wspy', array('title' => 'WorldSpy-Feeds verwalten')),
      ($sf_request->getModule() == 'feed') ? array('class' => 'selected') : array(),
      true
    );
?></ul>