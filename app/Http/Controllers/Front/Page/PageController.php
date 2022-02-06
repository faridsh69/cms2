<?php

namespace App\Http\Controllers\Front\Page;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Page;
use Auth;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(string $page_url = null, Request $request)
    {
        $page = Page::where('url', $page_url)->active()->first();
        abort_if(!$page, 404);

        return view('front.common.layout', ['list' => [], 'page' => $page]);
    }

    public function postSubmitForm($form_id, Request $request, \App\Models\Answer $answer)
    {
        $formModel = Form::find($form_id);
        if ($formModel->authentication === true) {
            if (!\Auth::check()) {
                return redirect()->route('auth.login');
            }
        }

        $form = \FormBuilder::create(\App\Forms\CustomeForm::class, ['formModel' => $formModel]);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $data = $form->getFieldValues();
        $mainData = $data;
        $files = [];
        foreach ($mainData as $key => $item) {
            // single file or multiple file
            if (is_object($item) || is_array($item)) {
                $files[$key] = $item;
                unset($data[$key]);
            }
        }
        $answer->activated = 1;
        $answer->user_id = Auth::id();
        $answer->form_id = $formModel->id;
        $answer->answers = serialize($data);
        $answer->save();

        // upload files
        foreach ($files as $column => $file) {
            $fileService = new \App\Cms\FileService();
            $fileService->save($file, $answer, $column);
        }

        // send sms to user and admin
        if ($formModel->notification === true) {
            $formSubmitted =  new \App\Notifications\FormSubmitted();

            $adminUser = \App\Models\User::getAdminUser();
            $adminUser->notify($formSubmitted);
            \Auth::user()->notify($formSubmitted);
        }

        $request->session()->flash('alert-success', 'Congratulation, Your answer saved successfully!');

        return redirect()->back();
    }
}
