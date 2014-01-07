<?php
  $deals = DealTable::getPayedCredits($deal->User);
  if( false !== $deals->search($deal) )
  {
    echo 'Auszahlung';
  }
  elseif( $deal->credit > 0)
  {
    echo 'Gutschrift';
  }
  elseif( $deal->sponsoring_id )
  {
    echo link_to( $deal->Sponsoring, $deal->Sponsoring->getEditLink() );
  }
