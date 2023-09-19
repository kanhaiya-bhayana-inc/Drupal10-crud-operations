<?php

namespace Drupal\my_crud\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Messenger;

class DeleteForm extends ConfirmFormBase
{
  public function getformId()
  {
    return 'delete_form';
  }

  public $cid;

  public function getQuestion()
  {
    return $this->t('Delete Record?');
  }

  public function getCancelUrl()
  {
    return new Url('my_crud.my_crud_controller_listing');
  }

  public function getDescription()
  {
    return $this->t('Are you sure want to delete this record?');
  }

  public function getConfirmText()
  {
    return $this->t('Delete it');
  }


  public function getCancelText()
  {
    return $this->t('Cancel');
  }

  public function buildForm(array $form, FormStateInterface $form_state,$cid=NULL){
    $this->cid = $cid;
    return parent::buildForm($form,$form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state){
    parent::validateForm($form,$form_state);
  }
  public function submitForm(array &$form, FormStateInterface $form_state){
    $query = \Drupal::database();
    $query->delete('my_crud')->condition('id',$this->cid)->execute();

    $this->messenger()->addMessage('Successfully deleted record');

    $form_state->setRedirect('my_crud.my_crud_controller_listing');
  }
}
