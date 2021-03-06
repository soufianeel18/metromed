@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
	            <div class="card-header text-light bg-info d-flex justify-content-between">
	            	<span>Ticket : {{ $ticket->title }}</span>
	            	<div class="d-flex">
	            		@can('viewHistory', $ticket)
	            			<a href="/ticket-admin/{{ $ticket->id }}/history" class="pr-2"><img src="/images/history.png" style="height: 16px; width: 16px;" alt="historique"></a>
	            		@endcan
	            		@can('update', $ticket)
		            		@if($ticket->status == "en cours")
		            			<a style="cursor: pointer" data-toggle="modal" data-target="#ticketModal" class="pr-2"><img src="/images/edit.png" style="height: 16px; width: 16px;" alt="modifier"></a>
		            		@endif
	            		@endcan
	            		@can('delete', $ticket)
		            		<form action="/ticket-admin/{{ $ticket->id }}" method="post">
		            			@csrf
	                    		@method('DELETE')
								<button style="border: none; padding: 0px; background-color: transparent;"><img src="/images/delete.svg" style="height: 16px; width: 16px;" alt="supprimer"></button>
							</form>
						@endcan
	            	</div>
	            </div>
				<div class="card-body">
					<span><strong>Date du début : </strong>{{ \Carbon\Carbon::parse($ticket->start_date)->translatedFormat('d F Y à H\hi') }}</span>
					<hr>
					<span><strong>Date de fin : </strong>{{ \Carbon\Carbon::parse($ticket->end_date)->translatedFormat('d F Y à H\hi') }}</span>
					<hr>
					<span><strong>Type : </strong>{{ $ticket->type }}</span>
					<hr>
					<div class="d-flex justify-content-between align-items-center">
						<div><span><strong>Etat : </strong>
							@switch($ticket->status)
							@case("en cours")
								<strong  class="text-success">{{ $ticket->status }}</strong>
							@break
							@case("fermée")
								<strong  class="text-danger">{{ $ticket->status }}</strong>
							@break
							@case("archivée")
								<strong  class="text-secondary">{{ $ticket->status }}</strong>
							@break
							@endswitch
						</span></div>
						@can('update', $ticket)
						<div class="d-flex">
							@if($ticket->status != "archivée")
								<div class="pr-2">
									<form action="/ticket-admin/status/{{ $ticket->id }}" method="post">
										@csrf
			                    		@method('PATCH')
			                    		<input type="text" name="status" value="archivée" hidden="">
										<button class="btn btn-secondary">Archiver</button>
									</form>
								</div>
							@endif
							@if($ticket->status != "fermée")
								<div class="pr-2">
									<form action="/ticket-admin/status/{{ $ticket->id }}" method="post">
										@csrf
			                    		@method('PATCH')
			                    		<input type="text" name="status" value="fermée" hidden="">
										<button class="btn btn-danger">Fermer</button>
									</form>
								</div>
							@endif
							@if($ticket->status != "en cours")
								<div class="pr-2">
									<form action="/ticket-admin/status/{{ $ticket->id }}" method="post">
										@csrf
			                    		@method('PATCH')
			                    		<input type="text" name="status" value="en cours" hidden="">
										<button class="btn btn-success">En cours</button>
									</form>
								</div>
							@endif
						</div>
						@endcan
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card mb-4">
				@if($ticket->user->role == 'technicien')
	            	<div class="card-header text-light bg-info">Technicien : {{ $ticket->user->name }}</div>
	            @else
	            	<div class="card-header text-light bg-info">Commercial : {{ $ticket->user->name }}</div>
	            @endif
				<div class="card-body">
					<span><strong>Email : </strong>{{ $ticket->user->email }}</span>
					<hr>
					<span><strong>Téléphone : </strong>{{ $ticket->user->phone }}</span>
				</div>
			</div>
		</div>
	</div>
	<hr>
	<div class="row m-2">
		<div class="col-md-12">
			@if($comments->count() != 0)
				@foreach($comments as $comment)
					<div class="row d-flex justify-content-between">
						<div>
							<strong>{{ $comment->user->username }}</strong> (<i>{{ $comment->user->name }}</i>)
						</div>
						<div class="text-secondary">{{ \Carbon\Carbon::parse($comment->created_at)->translatedFormat('d F Y à H\hi') }}</div>
					</div>
					<div class="row pb-3">
						<div class="col-md-1"></div>
						<div class="col-md-11">
							@if(Auth::User()->username == $comment->user->username)
								<div class="p-2 m-2 d-flex justify-content-between" style="border-radius: 10px; background-color: #C5EFF7;">
							@else 
								<div class="p-2 m-2 d-flex justify-content-between" style="border-radius: 10px; background-color: #F2F1EF;">
							@endif
								<div>
								@if($comment->created_at == $comment->updated_at)
	                                	<p style="white-space: pre-wrap;  overflow-wrap: normal;">{{ $comment->text }}</p>
	                                @else
	                                	<p style="white-space: pre-wrap;  overflow-wrap: normal;">{{ $comment->text }} <br><i>(Modifié)</i></p>
	                                @endif
	                            </div>
	                            <div>
	                            	@if($ticket->status == "en cours")
										@can('update', $comment)
											<a style="cursor: pointer" data-toggle="modal" data-target="#comment{{ $comment->id }}" class="pr-1">
												<img src="/images/edit.png" style="height: 16px; width: 16px;" alt="modifier">
											</a>
											@include('comments.edit')
										@endcan
										@if($comment->created_at != $comment->updated_at)
											<a style="cursor: pointer" data-toggle="modal" data-target="#commEvent{{ $comment->id }}" class="pr-1">
												<img src="/images/history.png" style="height: 16px; width: 16px;" alt="historique">
											</a>
											@include('comments.show')
										@endif
									@endif
								</div>
							</div>
						</div>
					</div>
				@endforeach
			@endif
			@can('createComment', $ticket,\App\Comment::class)
				@if($ticket->status == "en cours")
				<hr>
				<form action="/comment/{{ $ticket->id }}" method="post">
	            	@csrf
	            	<div class="row">
		                <div class="col-md-11 mt-2">
		                	<textarea id="text" name="text" class="form-control" placeholder="Ajouter un commentaire" style="height: 40px; resize: none;" required></textarea>
		                </div>
		            	<div class="col-md-1 mt-2">
		            		<button class="btn btn-info">Envoyer</button>
		            	</div>
		            </div>
	        	</form>
	        	@endif
        	@endcan
		</div>
	</div>
	<hr>
	<div class="row d-flex justify-content-center">
		@if($techniciens->count() != 0 || $commerciaux->count() != 0)
			@can('update', $ticket)
				<div class="pr-2  pb-3">
					<button class="btn btn-danger" data-toggle="modal" data-target="#listRessourceModal">Réaffectation de ressources</button>
				</div>
			@endcan
		@endif
		@can('view', $ticket->user)
			<div>
				@if($ticket->user->role == 'technicien')
					<button class="btn btn-danger" onclick="window.location.href='/technicien/{{ $ticket->user->id }}'">Tickets de {{ $ticket->user->name }}</button>
				@else
					<button class="btn btn-danger" onclick="window.location.href='/commercial/{{ $ticket->user->id }}'">Tickets de {{ $ticket->user->name }}</button>
				@endif
			</div>
		@endcan
	</div>
	@include('tickets-admin.list_resources')
	@include('tickets-admin.edit')
</div>
@endsection
