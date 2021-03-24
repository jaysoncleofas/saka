            <footer class="main-footer">
                <div class="footer-left">
                Copyright &copy; 2020 <div class="bullet"></div> <a href="{{ route('dashboard.index') }}">{{ config('yourconfig.resort')->name }}</a>
                </div>
                <div class="footer-right">
                {{-- 2.3.0 --}}
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('stisla/assets/js/jquery.nicescroll.min.js') }}"></script>    
    <script src="{{ asset('stisla/assets/js/stisla.js') }}"></script>
    <script src="{{ asset('stisla/assets/js/custom.js') }}"></script>
    <script src="{{ asset('stisla/assets/js/scripts.js') }}"></script>
    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('fullcalendar-5.5.1/lib/main.min.js') }}"></script>
    {{-- <script src="{{ asset('select2/dist/js/select2.full.min.js') }}"></script> --}}
    <script src="{{ asset('stisla/assets/js/custom.js') }}"></script>
    @include('partials.notification')
</body>
</html>