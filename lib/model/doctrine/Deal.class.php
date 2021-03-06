<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Deal extends BaseDeal
{
  /**
   * Returns this object as a string. Used by helpers in forms and backend.
   *
   * @return String
   */
  public function __toString()
  {
    if(!$this->sponsoring_id == Null)
    {
      return '[' . $this->id . '] ' . $this->User->username;
    }
    elseif(($this->sponsoring_id == Null) && ($this->payed == true))
    {
      return 'Auszahlung';
    }
    else
    {
      return '';
    }
  }

  /**
   * Creates a transaction
   *
   * @param Array $data from schema.
   * @return Deal newly saved.
   */
  public static function create( $data )
  {
    $d = new self();
    $d->fromArray( $data );
    $d->save();
    return $d;
  }

  /**
   * Updates the instance of this object with the information from the array. (if it exists on this object).
   *
   * @param array $data
   * @return this.
   */
  public function update($data)
  {
    $this->fromArray($data);
    $this->save();
    return $this;
  }

  /**
   * Updates the buyer details from a papal buyerInformation class.
   *
   * @param payPalbuyerInfo $details
   * @return void
   */
  public function savePayPalDetails( $details )
  {
    $buyer_name = $details->getPayerInfo()->getPayerName();
    $buyer_address = $details->getPayerInfo()->getAddress();
    return $this->update(
      array(
        "buyer_first_name" => $buyer_name->FirstName,
        "buyer_last_name" => $buyer_name->LastName,
        "buyer_street1" => $buyer_address->Street1,
        "buyer_street2" => $buyer_address->Street2,
        "buyer_city" => $buyer_address->CityName,
        "buyer_state" => $buyer_address->StateOrProvince,
        "buyer_zip" => $buyer_address->PostalCode,
        "buyer_country" => $buyer_address->CountryName,
        "buyer_email" => $details->getPayerInfo()->Payer,
      )
    );
  }

  /**
   * sets this object to be payed.
   *
   */
  public function setHasPayed()
  {
    $this->payed = true;
    $this->save();
  }

  /**
   * Retreives the clear amount of this Deal without tax.
   *
   * @param Boolean $credit
   * @return String
   */
  public function getClear( $credit = false )
  {
    return number_format( round( ($credit === true) ? $this->credit : $this->debit / 1.19, 2), 2);
  }
}