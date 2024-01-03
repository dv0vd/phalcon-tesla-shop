<?php

use Phalcon\Mvc\Controller;
use PHPMailer\PHPMailer\PHPMailer;

class IndexController extends Controller
{

    public function initialize(){

    }

    public function indexAction(){
      $this->view->title = 'Главная';
      $mirrors = Products::find([
        'conditions' => "product_type=0"
      ]);
      $this->view->mirrors = $mirrors;
    }

    public function sendAction() {
      $request =$this->request;
      if ($request->isPost() && $request->isAjax()) {
        $name = $request->getPost('name');
        $email = $request->getPost('email');
        $question = $request->getPost('question');
        require_once APP_PATH."/libraries/PHPMailer/PHPMailer.php";
        require_once APP_PATH."/libraries/PHPMailer/SMTP.php";
        require_once APP_PATH."/libraries/PHPMailer/Exception.php";
        $mail = new PHPMailer;
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'ssl://smtp.mail.ru';
        $mail->Port = 465;
        $mail->Username = 'belgorodteslaclub@mail.ru';
        $mail->Password = 'webdevelopment';
        $mail->From = ('belgorodteslaclub@mail.ru');
        $mail->FromName = ('BELGOROD TESLA CLUB');
        $mail->addAddress('belgorodteslaclub@mail.ru');
        $mail->Subject = 'Вопрос с сайта';
        $mail->isHTML(true);
        $mail->Body = "<p>Имя: $name</p><p>Почта: $email</p><center><p><b>Вопрос</b></p></center><p>$question</p>";
        if ($mail->send()) {
          return 'true';
        } else {
          return 'false';
        }
      }
    }

    public function contactAction()
    {
      $this->view->title = 'Контакты';
    }

    public function faqAction(){
      $this->view->title = 'FAQ';
      $faqGroups = FaqGroups::find([
        'conditions' => "faq_group_hidden=0",
        'order' => 'faq_group_position ASC'
      ]);
      $this->view->faqGroups = $faqGroups;
    }

    public function teamAction(){
      $this->view->title = 'Наша команда';
      $departments = Departments::find([
        'conditions' => "department_hidden=0",
        'order' => 'department_position ASC'
      ]);
      $this->view->departments = $departments;
    }

    public function catalogAction()
    {
      $this->view->title = 'Каталог товаров';
      $mirrors = Products::find([
        'conditions' => "product_type=0"
      ]);
      $this->view->mirrors = $mirrors;
      $modules = Products::find([
        'conditions' => "product_type=1"
      ]);
      $this->view->modules = $modules;
    }

    public function productAction($id)
    {
      $this->view->title = 'Описание товара';
      try{
        $product = Products::find([
          'conditions' => "product_id=$id"
        ]);
      } catch (Exception $e){
        return $this->response->redirect('/index/catalog');
      }
      if(count($product) == 0) {
        return $this->response->redirect('/index/catalog');
      }
      $this->view->product = $product;
    }

    public function cartAction()
    {
      $this->view->title = 'Корзина';
      $products = Products::find();
      $this->view->products = $products;
    }

    public function addToCartAction() {
      $request =$this->request;
      if ($request->isPost() && $request->isAjax()) {
        if($this->session->product_id != NULL) {
          $array = $this->session->product_id;
          $array[$request->getPost('id')] = $request->getPost('id');
          $this->session->set('product_id', $array);
          $array = $this->session->product_count;
          $array[$request->getPost('id')] = 1;
          $this->session->set('product_count', $array);
        } else {
          $this->session->product_id = [$request->getPost('id') => $request->getPost('id')];
          $this->session->product_count = [$request->getPost('id') => 1];
        }
        return 'true';
      }
    }

    public function removeFromCartAction() {
      $request =$this->request;
      if ($request->isPost() && $request->isAjax()) {
        $array = $this->session->product_id;
        unset($array[$request->getPost('id')]);
        $this->session->set('product_id', $array);
        $array = $this->session->product_count;
        unset($array[$request->getPost('id')]);
        $this->session->set('product_count', $array);
        return 'true';
      }
    }

    public function changeCartCountAction() {
      $request =$this->request;
      if ($request->isPost() && $request->isAjax()) {
        $array = $this->session->product_count;
        $array[$request->getPost('id')] = $request->getPost('count');
        $this->session->set('product_count', $array);
        $totalSum = 0;
        foreach($this->session->product_id as $product) {
          $id = $product;
          $product_info = Products::find("product_id='$id'");
          $count = $this->session->product_count[$id];
          $totalSum += $product_info[0]->product_price * $count;
        }
        return "$totalSum";
      }
    }

    public function orderAction() {
      $products = Products::find();
      $this->view->products = $products;
      $this->view->title = 'Оформление заказа';
    }

    public function processOrderAction() {
      $request =$this->request;
      if ($request->isPost() && $request->isAjax()) {
        $newOrder = new Orders();
        $newOrder->order_name = $request->getPost('name');
        $newOrder->order_surname = $request->getPost('surname');
        $newOrder->order_email = $request->getPost('email');
        $this->session->set('email', $request->getPost('email'));
        $newOrder->order_address = $request->getPost('address');
        $newOrder->order_comment = $request->getPost('comment');
        $newOrder->order_sum = $request->getPost('sum');
        $newOrder->order_date = date('Y-m-d H:i:s');
        $date = $newOrder->order_date;
        if($newOrder->save()) {
          $order = Orders::find("order_date='$date'");
          $sum=$order[0]->order_sum;
          $id=$order[0]->order_id;
          $this->session->set('ordered', $sum);
          $this->session->set('order_id', $id);
          foreach($this->session->product_id as $product) {
            $newOrdersProducts = new OrdersProducts();
            $newOrdersProducts->order_id = $id;
            $newOrdersProducts->product_id = $product;
            $newOrdersProducts->product_count = $this->session->product_count[$product];
            $newOrdersProducts->save();
          }
          $this->session->remove('product_id');
          $this->session->remove('product_count');
          return 'true';
        } else {
          return 'false';
        }
      }
    }

    public function payAction() {
      $this->view->title = 'Оплата заказа';
      if(!$this->session->has('ordered')) {
        return $this->response->redirect('/');
      }
    }

    public function successAction() {
      if(!$this->session->has('order_id')) {
        return $this->response->redirect('/');
      }
      $this->view->title = 'Успех!';
      $id = $this->session->order_id;
      $order = Orders::find("order_id='$id'");
      $order[0]->order_paid = '1';
      $order[0]->update();
      require_once APP_PATH."/libraries/PHPMailer/PHPMailer.php";
      require_once APP_PATH."/libraries/PHPMailer/SMTP.php";
      require_once APP_PATH."/libraries/PHPMailer/Exception.php";
      $mail = new PHPMailer;
      $mail->CharSet = 'UTF-8';
      $mail->isSMTP();
      $mail->SMTPAuth = true;
      $mail->Host = 'ssl://smtp.mail.ru';
      $mail->Port = 465;
      $mail->Username = 'wisemirror@mail.ru';
      $mail->Password = 'webdevelopment';
      $mail->From = ('wisemirror@mail.ru');
      $mail->FromName = ('WISEMIRROR');
      $email = $this->session->email;
      $mail->addAddress("$email");
      $mail->Subject = 'Номер заказа';
      $mail->isHTML(true);
      $mail->Body = "<h1>Спасибо, что пользуетесь нашими товарами и услугами!</h1><p>Номер заказа: $id</p>";
      $mail->send();
      $this->session->remove('order_id');
      $this->session->remove('ordered');
      $this->session->remove('email');
    }

}

 ?>
