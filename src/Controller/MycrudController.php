<?php

namespace Drupal\my_crud\Controller;

use Consolidation\Filter\OperatorInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\url;
use Drupal\Core\Link;
use Drupal\Core\Messenger;

class MycrudController extends ControllerBase{
  public function Listing(){
    // table header...
    $header_table = ['id'=>$this->t('ID'),'name'=>$this->t('Name'),'age'=>$this->t('Age'),'opt'=>$this->t('Operation'),'
      opt1'=>$this->t('Operation',)];

    $row = [];

    $con = Database::getConnection();
    $query = $con->select('my_crud','m');
    $query->fields('m',['id','name','age']);
    $result = $query->execute()->fetchAll();

    foreach($result as $value){
      $delete = Url::fromUserInput('/my_crud/form/delete/'.$value->id);
      $edit = Url::fromUserInput('/my_crud/form/data?id='.$value->id);

      $row[] = ['id'=>$value->id,'namae'=>$value->name,'age'=>$value->age,'opt'=>Link::
                fromTextAndUrl('Edit',$edit)->toString(),'opt1'=>Link::fromTextAndUrl('Delete',$delete)->toString()];

    }

    $add = Url::fromUserInput('/my_crud/form/data');
    $text = "Add user";
    $data['table'] = ['#type'=>'table','#header'=>$header_table,'#rows'=>$row,'#empty'=>$this->t('No record found'),'#caption'=>Link::fromTextAndUrl($text,$add)->toString()];

    $this->messenger()->addMessage('Records Listed');
    return $data;
  }
}

?>
