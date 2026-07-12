<?php
namespace App\Http\Controllers\Admin;

use App\Comment;
use App\Http\Requests\Admin\CommentRequest;
use System\Auth\Auth;

class CommentsController extends AdminController
    {

    public function index()
        {
        $comments = Comment::all();
        return view('admin.comments.index', compact('comments'));
        }

    public function show($id)
        {
        $comment = Comment::find($id);
        return view('admin.comment.show', compact('comment'));
        }

    public function approved($id)
        {
        $comment = Comment::find($id);

        if ($comment->approved == 0) {
            Comment::update(['id' => $id, 'approved' => 1]);
            }
        else {
            Comment::update(['id' => $id, 'approved' => 0]);
            }
        }

    /**
     * ویرایش متن یک کامنت - از طریق AJAX
     * ارسال می‌شود به: admin/comments/update
     */
    public function update()
        {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $commentText = isset($_POST['comment']) ? $_POST['comment'] : '';

        if (!$id) {
            header('Content-Type: application/json');
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'شناسه کامنت ارسال نشده است']);
            exit;
            }

        $comment = Comment::find($id);

        if (!$comment) {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'کامنت یافت نشد']);
            exit;
            }

        Comment::update([
            'id'      => $id,
            'comment' => $commentText,
        ]);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'کامنت با موفقیت ویرایش شد',
            'id'      => $id,
            'comment' => $commentText,
        ]);
        exit;
        }

    /**
     * ثبت پاسخ به یک کامنت - از طریق AJAX
     * ارسال می‌شود به: admin/comments/answer
     */
    public function answer()
        {
        $parentId = isset($_POST['id']) ? $_POST['id'] : null;
        $commentText = isset($_POST['comment']) ? $_POST['comment'] : '';

        if (!$parentId) {
            header('Content-Type: application/json');
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'شناسه کامنت والد ارسال نشده است']);
            exit;
            }

        $parentComment = Comment::find($parentId);

        if (!$parentComment) {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'کامنت والد یافت نشد']);
            exit;
            }

        $inputs = [
            'user_id'   => Auth::user()->id,
            'post_id'   => $parentComment->post_id,
            'comment'   => $commentText,
            'parent_id' => $parentId,
            'approved'  => 1,
            'status'    => 0,
        ];

        Comment::create($inputs);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'پاسخ با موفقیت ثبت شد',
        ]);
        exit;
        }

    public function edit($id)
        {
        $comment = Comment::find($id);
        return view('admin.comments.edit', compact('comment'));
        }

    public function deleteWithDescendants($id)
        {
        $comment = Comment::find($id);

        if (!$comment) {
            return false;
            }

        $children = Comment::where('parent_id', $id)->get();

        foreach ($children as $child) {
            $this->deleteWithDescendants($child->id);
            }

        return $comment->delete($id);

        }

    public function destroy($id)
        {
        $this->deleteWithDescendants($id);
        // return back();
        }


    // public function destroy($id)
    //     {
    //     Comment::delete($id);
    //     return back();
    //     }

    // مشکل
    // وقتی دسته والد رو حذف میکنی, باید دسته های فرزند هم حذف بشوند
    }