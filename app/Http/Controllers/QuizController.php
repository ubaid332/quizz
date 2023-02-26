<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests\QuizzRequest;
use App\Http\Resources\QuizResource;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizzes = QuizResource::collection(Quiz::all());
        
        return response()->json([
            "success" => true,
            "errors" => null,
            'data'=> $quizzes
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuizzRequest $request)
    {

        $response = [
            "success" => false,
            "errors" => null,
            "msg" => 'Something went wrong',
            "data" => null,
        ];

        DB::beginTransaction();

        try {
        
           $quizz= Quiz::create([
                'title' =>  $request->title,
                'description' =>  $request->description,
                'status' =>  $request->status,
            ]);

            // Insert Quizz Questions
            if($quizz && $request->questions){
            foreach($request->questions as $each_ques){
        

                $ques= Question::create([
                    'quizz_id' =>  $quizz->id,
                    'question' =>  $each_ques['question'],
                    'mandatory' =>  $each_ques['mandatory'],
                ]);


                // Insert Answers
                if($ques && $each_ques['answers']){
                    foreach($each_ques['answers'] as $each_ans){
                
                        $ans= Answer::create([
                            'question_id' =>  $ques->id,
                            'answer' =>  $each_ans['answer'],
                            'right_answer' =>  $each_ans['right_answer'],
                        ]);
                    }

                }

            }
        }
        
            DB::commit();

            $response = [
                "success" => true,
                "errors" => null,
                "msg" => 'Quizz Created',
                "data" => null,
            ];
            
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong

            $response = [
                "success" => false,
                "errors" => $e,
                "msg" => 'something went wrong',
                "data" => null,
            ];
        }
        
        return response()->json($response, 200);
    }

}
