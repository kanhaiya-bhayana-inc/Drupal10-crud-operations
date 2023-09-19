<?php

namespace Drupal\my_crud\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Messenger;
use Drupal\Core\Link;

class MyCrudForm extends FormBase{
  public function getFormid(){
    return 'mycrud_form';
  }

  public function buildform(array $form, FormStateInterface $form_state){
    $conn = Database::getConnection();
    $record = [];
    if ($_GET['id']){
      $query= $conn->select('my_crud','m')->condition('id',$_GET['id'])->fields('m');
      $record = $query->execute()->fetchAssoc();
    }
    $form['name']=['#type'=>'textfield','#title'=>$this->t('Name'),'#required'=>TRUE,'#default_value'=>(
      isset($record['name'])&&$_GET['id'])?$record['name']:'',];

      $form['age']=['#type'=>'textfield','#title'=>$this->t('Age'),'#required'=>TRUE,'#default_value'=>(
        isset($record['age'])&&$_GET['id'])?$record['age']:'',];

      $form['action'] = ['#type'=>'action',];
      $form['action']['submit'] = ['#type'=>'submit','#value'=>$this->t('Save')];

      $form['action']['reset']=['#type'=>'button','#value'=>$this->t('Reset'),'#attributes'=>['onclick'=>'
        this.form.reset(); return false;',],];

      $link = Url::fromUserInput('/my_crud/');
      $form['action']['cancel'] = ['#markup'=>Link::fromTextAndUrl($this->t('Back to page'),$link)->toString(),];

      return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state){
    $name = $form_state->getValue('name');
    $age = $form_state->getValue('age');
    if (!preg_match('/[^A-za-z]/',$name)){
      $form_state->setErrorByName('name',$this->t('Please enter a valid name'));
    }

    if (!preg_match('/^(1[89]|[2-9]\d)/',$age)){
      $form_state->setErrorByName('age',$this->t('Please enter a valid age'));
    }

    parent::validateForm($form,$form_state);
  }
  public function submitForm(array &$form, FormStateInterface $form_state){
    $field = $form_state->getValues();
    $name = $field['name'];
    $age = $field['age'];

    if (isset($_GET['id'])){
      $field = ['name'=>$name, 'age'=>$age,];
      $query = \Drupal::database();
      $query->update('my_crud')->fields($field)->condition('id',$_GET['id'])->execute();
      $this->messenger()->addMessage('Successfully Updated Records');
      $form_state->setRedirect('my_crud.my_crud_controller_listing');
    }
    else{
      $field = ['name'=>$name, 'age'=>$age,];
      $query = \Drupal::database();
      $query->insert('my_crud')->fields($field)->execute();
      $this->messenger()->addMessage('Information has been saved successfully.');

      $form_state->setRedirect('my_crud.my_crud_controller_listing');
    }
  }
}

?>
