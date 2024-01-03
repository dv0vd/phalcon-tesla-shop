<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Message\UploadedFile;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Image\Adapter\Gd;
use Phalcon\Image\Enum;

class AdminController extends Controller
{

  public function indexAction()
  {
    $this->view->title = 'Кабинет администратора';
    if(!$this->session->has('user_id')) {
      return $this->response->redirect('/admin/login');
    }
    $this->view->faqGroups = FaqGroups::find();
    $this->view->faqs = Faqs::find();
    $this->view->departments = Departments::find();
    $this->view->employees = Employees::find();
    $this->view->products = Products::find();
    $this->view->orders = Orders::find(['order' => 'order_date DESC']);
    $this->view->ordersProducts = OrdersProducts::find();
  }

  public function loginAction() {
    $this->view->title = 'Вход';
    if($this->session->has('user_id')) {
      return $this->response->redirect('/admin');
    }
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $login = $request->getPost('login');
      $users = Users::find("user_login= '".$login."'");
      if(count($users) == 0) {
        return 'no_users';
      } else {
        $password = $request->getPost('password');
        if($this->security->checkHash($password, $users[0]->user_password)){
          if($users[0]->user_role != 'admin'){
            return 'not_admin';
          } else {
            $this->session->user_id = $users[0]->user_id;
            return 'success';
          }
        } else {
            return 'password_error';
        }
      }
    }
  }

  public function destroyAction() {
    $this->session->destroy();
    return $this->response->redirect('/admin');
  }

  public function changePasswordAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $password = $request->getPost('password');
      $user_id = $this->session->get('user_id');
      $users = Users::find("user_id= '".$user_id."'");
      $users[0]->user_password = $this->security->hash($password);
      $users[0]->save();
      return 'true';
    }
  }

  public function addFaqGroupAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $newFaqGroup = new FaqGroups();
      $newFaqGroup->faq_group_title = $request->getPost('header');
      if($request->getPost('hidden') == 'true'){
        $newFaqGroup->faq_group_hidden = 1;
      } else {
        $newFaqGroup->faq_group_hidden = 0;
      }
      $faqGroups = FaqGroups::find();
      $newFaqGroup->faq_group_position = count($faqGroups);
      if($newFaqGroup->save()) {
        return 'true';
      } else {
        return 'false';
      }
    }
  }

  public function removeFaqGroupAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $faqGroup = FaqGroups::find("faq_group_id='".$request->getPost('id')."'");
      $pos = $faqGroup[0]->faq_group_position;
      if($faqGroup->delete()) {
        $faqGroups = FaqGroups::find("faq_group_position>'$pos'");
        foreach($faqGroups as $faqGroup) {
          $faqGroup->faq_group_position = $faqGroup->faq_group_position - 1;
          $faqGroup->update();
        }
        return 'true';
      } else {
        return 'false';
      }
    }
  }

  public function startEditFaqGroupAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $faqGroup = FaqGroups::find("faq_group_id='".$request->getPost('id')."'");
      $response = [
        'id' => $faqGroup[0]->faq_group_id,
        'title' => $faqGroup[0]->faq_group_title,
        'position' => $faqGroup[0]->faq_group_position,
        'hidden' => $faqGroup[0]->faq_group_hidden
      ];
      return json_encode($response);
    }
  }

  public function editFaqGroupAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $faqGroup = FaqGroups::find("faq_group_id='".$request->getPost('id')."'");
      $faqGroup[0]->faq_group_title = $request->getPost('title');
      if($request->getPost('hidden') == 'true'){
          $faqGroup[0]->faq_group_hidden = 1;
      } else {
          $faqGroup[0]->faq_group_hidden = 0;
      }
      $editRec = FaqGroups::find("faq_group_position='".$request->getPost('position')."'");
      $oldPosition = $faqGroup[0]->faq_group_position;
      $editRec[0]->faq_group_position = $oldPosition;
      if(!$editRec[0]->update()) return 'false';
      $faqGroup[0]->faq_group_position = $request->getPost('position');
      if($faqGroup[0]->save()) {
        return 'true';
      } else {
        return 'false';
      }
    }
  }

  public function addFaqAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $newFaq = new Faqs();
      $newFaq->faq_title = $request->getPost('header');
      $newFaq->faq_description = $request->getPost('answer');
      $group = $request->getPost('group');
      if($request->getPost('hidden') == 'true'){
        $newFaq->faq_hidden = 1;
      } else {
        $newFaq->faq_hidden = 0;
      }
      $faqGroups = FaqGroups::find("faq_group_title='".$group."'");
      $newFaq->faq_group_id = $faqGroups[0]->faq_group_id;
      $id = $faqGroups[0]->faq_group_id;
      $faqs = Faqs::find("faq_group_id='$id'");
      $newFaq->faq_position = count($faqs);
      if($newFaq->save()) {
        return 'true';
      } else {
        return 'false';
      }
    }
  }

  public function removeFaqAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $faq = Faqs::find("faq_id='".$request->getPost('id')."'");
      $pos = $faq[0]->faq_position;
      if($faq->delete()) {
        $faqs = Faqs::find("faq_position>'$pos'");
        foreach($faqs as $faq) {
          $faq->faq_position = $faq->faq_position - 1;
          $faq->update();
        }
        return 'true';
      } else {
        return 'false';
      }
    }
  }

  public function startEditFaqAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $faq = Faqs::find("faq_id='".$request->getPost('id')."'");
      $faq_group = FaqGroups::find("faq_group_id='".$faq[0]->faq_group_id."'");
      $faq_positions = Faqs::find("faq_group_id='".$faq[0]->faq_group_id."'");
      $response = [
        'id' => $faq[0]->faq_id,
        'title' => $faq[0]->faq_title,
        'position' => $faq[0]->faq_position,
        'positions_count' => count($faq_positions),
        'hidden' => $faq[0]->faq_hidden,
        'group' => $faq_group[0]->faq_group_title
      ];
      return json_encode($response);
    }
  }

  public function editFaqAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $faq = Faqs::find("faq_id='".$request->getPost('id')."'");
      $faq[0]->faq_title = $request->getPost('title');
      if($request->getPost('hidden') == 'true'){
          $faq[0]->faq_hidden = 1;
      } else {
          $faq[0]->faq_hidden = 0;
      }
      $editRec = Faqs::find("faq_position='".$request->getPost('position')."'");
      $oldPosition = $faq[0]->faq_position;
      $editRec[0]->faq_position = $oldPosition;
      if(!$editRec[0]->update()) return 'false';
      $faq[0]->faq_position = $request->getPost('position');
      $newGroup = FaqGroups::find("faq_group_title='".$request->getPost('group')."'");
      $faq[0]->faq_group_id = $newGroup[0]->faq_group_id;
      if($faq[0]->save()) {
        return 'true';
      } else {
        return 'false';
      }
    }
  }

  public function addDepartmentAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $newDepartment = new Departments();
      $newDepartment->department_name = $request->getPost('name');
      if($request->getPost('hidden') == 'true'){
        $newDepartment->department_hidden = 1;
      } else {
        $newDepartment->department_hidden = 0;
      }
      $departments = Departments::find();
      $newDepartment->department_position = count($departments);
      if($newDepartment->save()) {
        return 'true';
      } else {
        return 'false';
      }
    }
  }

  public function removeDepartmentAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $department = Departments::find("department_id='".$request->getPost('id')."'");
      $pos = $department[0]->department_position;
      if($department->delete()) {
        $departments = Departments::find("department_position>'$pos'");
        foreach($departments as $department) {
          $department->department_position = $department->department_position - 1;
          $department->update();
        }
        return 'true';
      } else {
        return 'false';
      }
    }
  }

  public function startEditDepartmentAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $departments = Departments::find("department_id='".$request->getPost('id')."'");
      $response = [
        'id' => $departments[0]->department_id,
        'name' => $departments[0]->department_name,
        'position' => $departments[0]->department_position,
        'hidden' => $departments[0]->department_hidden
      ];
      return json_encode($response);
    }
  }

  public function editDepartmentAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $department = Departments::find("department_id='".$request->getPost('id')."'");
      $department[0]->department_name = $request->getPost('name');
      if($request->getPost('hidden') == 'true'){
          $department[0]->department_hidden = 1;
      } else {
          $department[0]->department_hidden = 0;
      }
      $editRec = Departments::find("department_position='".$request->getPost('position')."'");
      $oldPosition = $department[0]->department_position;
      $editRec[0]->department_position = $oldPosition;
      if(!$editRec[0]->update()) return 'false';
      $department[0]->department_position = $request->getPost('position');
      if($department[0]->save()) {
        return 'true';
      } else {
        return 'false';
      }
    }
  }

  public function addEmployeeAction() {
    $request = $this->request;
    if ($request->isPost() && $request->isAjax()) {
      $newEmployee = new Employees();
      $newEmployee->employee_name = $request->getPost('employeeFormName');
      $newEmployee->employee_post = $request->getPost('employeeFormPost');
      $department = $request->getPost('employeeFormDepartment');
      if($request->getPost('employeeFormHidden') == 'on'){
        $newEmployee->employee_hidden = 1;
      } else {
        $newEmployee->employee_hidden = 0;
      }
      $employees = Employees::find();
      $departments = Departments::find("department_name='".$department."'");
      $newEmployee->department_id = $departments[0]->department_id;
       if ($this->request->hasFiles() == true) {
        $file = $this->request->getUploadedFiles()[0];
        $oldName = $_FILES['employeeFormPhoto']['name'];
        $_FILES['employeeFormPhoto']['name'] = rand()."_".$oldName;
        $fileName = $_FILES['employeeFormPhoto']['name'];
        $file->moveTo("img/team/$fileName");
        $newEmployee->employee_photo_location = "img/team/$fileName";
      } else {
        $newEmployee->employee_photo_location = 'img/default.png';
      }
      $id = $departments[0]->department_id;
      $employees = Employees::find("department_id='$id'");
      $newEmployee->employee_position = count($employees);
      if($newEmployee->save()) {
        return 'true';
      } else {
        return 'false';
      }
    }
  }

  public function removeEmployeeAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $employee = Employees::find("employee_id='".$request->getPost('id')."'");
      $pos = $employee[0]->employee_position;
      if($employee->delete()) {
        $employees = Employees::find("employee_position>'$pos'");
        foreach($employees as $employee) {
          $employee->employee_position = $employee->employee_position - 1;
          $employee->update();
        }
        return 'true';
      } else {
        return 'false';
      }
    }
  }

  public function startEditEmployeeAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $employee = Employees::find("employee_id='".$request->getPost('id')."'");
      $employee_positions = Employees::find("department_id='".$employee[0]->department_id."'");
      $employee = Employees::find("employee_id='".$request->getPost('id')."'");
      $department = Departments::find("department_id='".$employee[0]->department_id."'");
      $response = [
        'id' => $employee[0]->employee_id,
        'name' => $employee[0]->employee_name,
        'post' => $employee[0]->employee_post,
        'position' => $employee[0]->employee_position,
        'positions_count' => count($employee_positions),
        'hidden' => $employee[0]->employee_hidden,
        'department' => $department[0]->department_name,
        'photo' => $employee[0]->employee_photo_location
      ];
      return json_encode($response);
    }
  }

  public function editEmployeeAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $employee = Employees::find("employee_id='".$request->getPost('editEmployeeFormId')."'");
      $employee[0]->employee_name = $request->getPost('editEmployeeFormName');
      $employee[0]->employee_post = $request->getPost('editEmployeeFormPost');
      if($request->getPost('editEmployeeFormHidden') == 'on'){
          $employee[0]->employee_hidden = 1;
      } else {
          $employee[0]->employee_hidden = 0;
      }
      $editRec = Employees::find("employee_position='".$request->getPost('editEmployeeFormPosition')."'");
      $oldPosition = $employee[0]->employee_position;
      $editRec[0]->employee_position = $oldPosition;
      if(!$editRec[0]->update()) return 'false';
      $employee[0]->employee_position = $request->getPost('editEmployeeFormPosition');
      $newDepartment = Departments::find("department_name='".$request->getPost('editEmployeeFormDepartment')."'");
      $employee[0]->department_id = $newDepartment[0]->department_id;
      if($request->getPost('editEmployeeFormWithoutPhoto') == 'on'){
        $employee[0]->employee_photo_location = 'img/default.png';
      } else {
         if ($this->request->hasFiles() == true){
           $file = $this->request->getUploadedFiles()[0];
           $oldName = $_FILES['editEmployeeFormNewPhoto']['name'];
           $_FILES['editEmployeeFormNewPhoto']['name'] = rand()."_".$oldName;
           $fileName = $_FILES['editEmployeeFormNewPhoto']['name'];
           $file->moveTo("img/team/$fileName");
           $employee[0]->employee_photo_location = "img/team/$fileName";
         }

      }
      if($employee[0]->save()) {
        return 'true';
      } else {
        return 'false';
      }
    }
  }

  public function removeUnnecessaryEmployeePhotosAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $employees = Employees::find();
      $files = scandir(BASE_PATH."/public/img/team");
      $count = 0;
      for($i=2; $i<count($files); $i++) {
        $isFound = false;
        foreach ($employees as $employee) {
          if(strcmp("img/team/".$files[$i],$employee->employee_photo_location) == 0) {
            $isFound = true;
            break;
          }
        }
        if(!$isFound) {
          unlink("img/team/".$files[$i]);
          $count++;
        }
      }
      return "$count";
    }
  }

  public function addMirrorAction() {
    $request = $this->request;
    if ($request->isPost() && $request->isAjax()) {
      $newProduct= new Products();
      $newProduct->product_title = $request->getPost('mirrorFormHeader');
      $newProduct->product_price = $request->getPost('mirrorFormPrice');
      $newProduct->product_description = $request->getPost('mirrorDescription');
      if(strcmp($request->getPost('mirrorFormType'),"Автомобиль") == 0) {
        $newProduct->product_type = '0';
      } else {
        $newProduct->product_type = '1';
      }

      $file = $this->request->getUploadedFiles()[0];
      $image = new Gd($file->getTempName());
      $height = 350;
      $image->resize(null, $height, Enum::HEIGHT);
      $oldName = $file->getName();
      $fileName = rand()."_".$oldName;
      $image->save("img/products/$fileName");
      $newProduct->product_photo = "img/products/$fileName";
      if($newProduct->save()) {
        return 'true';
      } else {
        return 'false - '.$request->getPost('mirrorDescription');
      }
    }
  }

  public function removeMirrorAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $mirror = Products::find("product_id='".$request->getPost('id')."'");
      if($mirror->delete()) {
        return 'true';
      } else {
        return 'false';
      }
    }
  }

  public function uploadProductImgAction() {
    $file = $this->request->getUploadedFiles()[0];
    $oldName = $file->getName();
    $fileName = rand()."_".$oldName;
    $file->moveTo("img/products/description/$fileName");
    $CKEditorFuncNum = $this->request->getQuery('CKEditorFuncNum');
    $url = "/img/products/description/$fileName";
    $msg = 'Изображение успешно загружено';
    $re = "<script>window.parent.CKEDITOR.tools.callFunction('$CKEditorFuncNum', '$url', '$msg')</script>";
    echo $re;
  }

  public function startEditMirrorAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $product = Products::find("product_id='".$request->getPost('id')."'");
      if($product[0]->product_type == 0) {
        $type = "Автомобиль";
      } else {
        $type = "Аксуссуар";
      }
      $response = [
        'id' => $product[0]->product_id,
        'price' => $product[0]->product_price,
        'title' => $product[0]->product_title,
        'description' => $product[0]->product_description,
        'type' => $type,
        'photo' => $product[0]->product_photo
      ];
      return json_encode($response);
    }
  }

  public function editProductAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $product = Products::find("product_id='".$request->getPost('editMirrorFormId')."'");
      $product[0]->product_price = $request->getPost('editMirrorFormPrice');
      $product[0]->product_title = $request->getPost('editMirrorFormHeader');
      $product[0]->product_description = $request->getPost('editmirrorDescription');
      if(strcmp($request->getPost('editMirrorFormType'),"Автомобиль") == 0) {
        $product[0]->product_type = '0';
      } else {
        $product[0]->product_type = '1';
      }
      if ($this->request->hasFiles() == true){
        $file = $this->request->getUploadedFiles()[0];
        $image = new Gd($file->getTempName());
        $height = 350;
        $image->resize(null, $height, Enum::HEIGHT);
        $oldName = $file->getName();
        $fileName = rand()."_".$oldName;
        $image->save("img/products/$fileName");
        $product[0]->product_photo = "img/products/$fileName";
      }
      if($product[0]->update()) {
        return 'true';
      } else {
        return 'false';
      }
    }
  }

  public function removeOrderAction() {
    $request =$this->request;
    if ($request->isPost() && $request->isAjax()) {
      $order = Orders::find("order_id='".$request->getPost('id')."'");
      if($order->delete()) {
        return 'true';
      } else {
        return 'false';
      }
    }
  }

}

?>
