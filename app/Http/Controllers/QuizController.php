<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Quiz;

class QuizController extends Controller
{
    private $question_total = 2;
	private $answer=array('1'=>'dl', '2'=>'both');

	public function index(Request $request)
	{
		$data['label_q1'] = 'Question1: From which descriptive list starts';
		$data['label_q2'] = htmlspecialchars('Question2: The attribute of <form> tag');
		$data['text_result'] = 'Result Page';
		$data['text_correct'] = 'Correct Answer';
		$data['text_wrong'] = 'Wrong Answer';
		$data['text_skip'] = 'Skip Answer';

		if($request->session()->has('username')){
			$data['username'] = $request->session()->get('username');
		} else {
			$data['username'] = '';
		}

		for ($i=1; $i < $this->question_total+1; $i++) { 
			if($request->session()->has('question'.$i)){
				$data['question'.$i] = $request->session()->get('question'.$i);
			} else {
				$data['question'.$i] = '';
			}
		}

		$data['step'] = '1';

		if($request->session()->has('step')){
			$data['step'] = $request->session()->get('step');
		}

		return view('quiz', $data);
	}
	public function step1(Request $request)
	{
		$post_data = $request->all();
		$json = array();

		$validator = Validator::make($request->all(), [
            'username' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $request->session()->put('username', $post_data['username']);
		$request->session()->put('step', '2');

        return response()->json(['success' => true]);		
	}

	public function step2(Request $request)
	{
		$post_data = $request->all();

		$validator = Validator::make($request->all(), [
            'question1' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $request->session()->put('question1', $post_data['question1']);
		$request->session()->put('step', '3');

        return response()->json(['success' => true]);

	}
	public function step3(Request $request)
	{
		$post_data = $request->all();

		$validator = Validator::make($request->all(), [
            'question2' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $request->session()->put('question2', $post_data['question2']);
		$request->session()->put('step', '3');

        return response()->json(['success' => true]);
	}

	public function getResult(Request $request)
	{
		$json['skip_ans'] = 0;
		$json['correct_ans'] = 0;
		$json['wrong_ans'] = 0;
	
		for ($i=1; $i < $this->question_total+1; $i++) { 
			if($request->session()->has('question'.$i)){
				if($request->session()->get('question'.$i) == $this->answer[$i]){
					$json['correct_ans'] +=1;
				} else {
					$json['wrong_ans'] +=1;
				}
			} else {
				$json['skip_ans'] += 1;
			}
		}

		$quiz = new Quiz;
      
        $quiz->username = $request->session()->get('username');
        $quiz->correct_ans = $json['correct_ans'];
        $quiz->wrong_ans = $json['wrong_ans'];
        $quiz->skip_ans = $json['skip_ans'];
        $quiz->save();

		return response()->json($json);
	}

	public function refresh_data(Request $request)
	{
		$request->session()->flush();
		return response()->json(['success' => true]);
	}
}
