@foreach($comments as $comment)
<div class="d-flex">
    <!-- Parent comment-->
    <div class="flex-shrink-0"><img class="rounded-circle mt-1" src="{{ asset('images/avatar.png') }}" width="35" alt="..." /></div>
    <div class="ms-3 ml-2">
        <div class="fw-bold">
            @if(isset($comment->user))
                {{ strip_tags($comment->user->username) }} 
            @else
                User
            @endif
        </div>
        {!! strip_tags($comment->name) !!}
        <br>
        <span style="font-size: 14px; color: #9e9e9e;">{!! $comment->getTimeAgo($comment->create_date) !!}</span>
        <form class="child_comment mt-1">
            <!-- <div class="form-group">
                <a href="" class="text_reply hidefeature"><i class="fa fa-reply fa-xs"></i><span class="small"> reply</span></a>
            </div> -->
            <div class="form-group mb-5 form-reply">
                <input type="text" required name="name_reply" class="form-control" placeholder="{{ __('landingpage.detail.subbtext_typing_comment') }}"/>
                <input type="hidden" name="parent_id" value="{{ $comment->id }}" />
                <div class="row float-right p-0 mr-0 mt-2">
                    <input type="submit" class="btn btn-sm btn-outline-info py-0 btn-reply-comment" style="font-size: 0.8em;" value="{{ __('landingpage.detail.button_reply') }}" />
                </div>
            </div>
        </form>
        @include('landing-page.comments', ['comments' => $comment->replies])
    </div>
</div>
@endforeach
