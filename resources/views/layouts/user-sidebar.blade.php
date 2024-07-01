<!-- Left sidebar START -->
<div class="col-xl-3">
	<!-- Responsive offcanvas body START -->
	<div class="offcanvas-xl offcanvas-end" tabindex="-1" id="offcanvasSidebar">
		<!-- Offcanvas header -->
		<div class="offcanvas-header bg-light">
			<h5 class="offcanvas-title" id="offcanvasNavbarLabel">My profile</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasSidebar"
			        aria-label="Close"></button>
		</div>
		<!-- Offcanvas body -->
		<div class="offcanvas-body p-3 p-xl-0">
			<div class="bg-dark border rounded-3 p-3 w-100">
				<!-- Dashboard menu -->
				<div class="list-group list-group-dark list-group-borderless collapse-list">
					<a class="list-group-item {{ ($active_menu == 'profile') ? 'active' : '' }}" href="{{route('my.profile')}}"><i
							class="bi bi-pencil-square fa-fw me-2"></i>{{ __('default.Edit Profile')}}</a>
					<a class="list-group-item {{ ($active_menu == 'settings') ? 'active' : '' }}" href="{{ route('my.settings') }}"><i
							class="bi bi-gear fa-fw me-2"></i>{{ __('default.Settings')}}</a>
					<a class="list-group-item text-danger bg-danger-soft-hover"
					   href="#"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt fa-fw me-2"></i>{{ __('default.Sign Out')}}</a>
				</div>
			</div>
		</div>
	</div>
	<!-- Responsive offcanvas body END -->
</div>
<!-- Left sidebar END -->
