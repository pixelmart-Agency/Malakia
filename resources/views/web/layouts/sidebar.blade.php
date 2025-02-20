<!-- SIDEBAR -->
<div id="sidebar" class="sidebar">
    <div class="image-logo d-flex justify-content-center">
        <img src="{{asset('web/image/logo.png')}}" alt="no-logo">
    </div>
    <hr class="hr-logo">
    <!-- SIDEBAR MENU -->
    <ul class="sidebar-menu fixed-menu">
        <li>
            <a href="{{route('adminHome')}}" class="link-sidebar {{ activeRoute('adminHome') }}">
                <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 22 22"
                     fill="none">
                    <path
                        d="M6.33344 17.1844H15.6668C16.6486 17.1844 17.4446 16.3885 17.4446 15.4066V8.73998L11.0001 4.29553L4.55566 8.73998V15.4066C4.55566 16.3885 5.3516 17.1844 6.33344 17.1844Z"
                        stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    <path
                        d="M8.99957 14.0724C8.99957 13.0906 9.7955 12.2947 10.7773 12.2947H11.2218C12.2036 12.2947 12.9996 13.0906 12.9996 14.0724V17.1836H8.99957V14.0724Z"
                        stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>الرئيسية</span>
            </a>
        </li>
        <li>
            <a href="{{route('report.index')}}" class="link-sidebar {{ activeRoute('report.index') }}">
                <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 22 22"
                     fill="none">
                    <path
                        d="M16.3332 8.29529H12.5554V4.51751M14.7777 17.1842H7.22211C6.24027 17.1842 5.44434 16.3882 5.44434 15.4064V6.07307C5.44434 5.09123 6.24027 4.29529 7.22211 4.29529H12.7777L16.5554 8.07307V15.4064C16.5554 16.3882 15.7595 17.1842 14.7777 17.1842Z"
                        stroke="#565A5D" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>إدارة التقارير</span>
            </a>
        </li>
        <li>
            <a href="{{route('notification.index')}}" class="link-sidebar {{ activeRoute('notification.index') }}">
                <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 22 22"
                     fill="none">
                    <path
                        d="M8.33333 14.962C8.33333 14.962 8.33333 17.1842 11 17.1842C13.6667 17.1842 13.6667 14.962 13.6667 14.962M15.6667 8.96195V10.7397L17.4444 14.5175H4.55556L6.33333 10.7397V8.96195C6.33333 6.38463 8.42267 4.29529 11 4.29529C13.5773 4.29529 15.6667 6.38463 15.6667 8.96195Z"
                        stroke="#565A5D" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>إدارة التنبيهات</span>
            </a>
        </li>
        <li>
            <a href="{{route('notice.index')}}" class="link-sidebar {{ activeRoute('note.index') }}">
                <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 22 22"
                     fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M5.35781 10.7405C5.35781 7.6232 7.88492 5.09609 11.0023 5.09609C14.1196 5.09609 16.6467 7.6232 16.6467 10.7405C16.6467 13.8579 14.1196 16.385 11.0023 16.385C7.88492 16.385 5.35781 13.8579 5.35781 10.7405ZM11.0023 3.49609C7.00126 3.49609 3.75781 6.73954 3.75781 10.7405C3.75781 14.7415 7.00126 17.985 11.0023 17.985C15.0033 17.985 18.2467 14.7415 18.2467 10.7405C18.2467 6.73954 15.0033 3.49609 11.0023 3.49609ZM11.0023 8.42943C11.1986 8.42943 11.3578 8.27024 11.3578 8.07387C11.3578 7.8775 11.1986 7.71832 11.0023 7.71832C10.8059 7.71832 10.6467 7.8775 10.6467 8.07387C10.6467 8.27024 10.8059 8.42943 11.0023 8.42943ZM9.75781 8.07387C9.75781 7.38658 10.315 6.82943 11.0023 6.82943C11.6895 6.82943 12.2467 7.38658 12.2467 8.07387C12.2467 8.76116 11.6895 9.31832 11.0023 9.31832C10.315 9.31832 9.75781 8.76116 9.75781 8.07387ZM11.0005 10.8294C11.4423 10.8294 11.8005 11.1876 11.8005 11.6294V13.4072C11.8005 13.849 11.4423 14.2072 11.0005 14.2072C10.5587 14.2072 10.2005 13.849 10.2005 13.4072V11.6294C10.2005 11.1876 10.5587 10.8294 11.0005 10.8294Z"
                          fill="#565A5D"/>
                </svg>
                <span>إدارة البلاغات</span>
            </a>
        </li>
        <li>
            <a href="{{route('axesManagement')}}" class="link-sidebar {{ activeRoute('axesManagement') }}">
                <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 22 22"
                     fill="none">
                    <path
                        d="M8.55566 4.29529L4.55566 6.07307V17.1842L8.55566 15.4064M8.55566 4.29529V15.4064M8.55566 4.29529L13.4446 6.07307M8.55566 15.4064L13.4446 17.1842M13.4446 6.07307L17.4446 4.29529V15.4064L13.4446 17.1842M13.4446 6.07307V17.1842"
                        stroke="#565A5D" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>إدارة المحاور</span>
            </a>
        </li>
        <li>
            <a href="{{route('users.index')}}" class="link-sidebar {{  activeRoute('users.index') }}">
                <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 22 22"
                     fill="none">
                    <path
                        d="M14.3333 12.5175C16.1811 12.5175 16.9379 14.4267 17.2434 15.8028C17.4084 16.546 16.8074 17.1842 16.0461 17.1842H15.2222M13.4444 9.18418C14.7945 9.18418 15.6667 8.08976 15.6667 6.73973C15.6667 5.3897 14.7945 4.29529 13.4444 4.29529M12.0829 17.1842H5.4726C4.97082 17.1842 4.57599 16.7679 4.67598 16.2762C4.95203 14.9186 5.8536 12.5175 8.77777 12.5175C11.7019 12.5175 12.6035 14.9186 12.8796 16.2762C12.9795 16.7679 12.5847 17.1842 12.0829 17.1842ZM11.2222 6.73973C11.2222 8.08976 10.1278 9.18418 8.77777 9.18418C7.42774 9.18418 6.33332 8.08976 6.33332 6.73973C6.33332 5.3897 7.42774 4.29529 8.77777 4.29529C10.1278 4.29529 11.2222 5.3897 11.2222 6.73973Z"
                        stroke="#565A5D" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>إدارة المستخدمين</span>
            </a>
        </li>
        <li>
            <a href="{{route('adminHome')}}" class="link-sidebar">
                <svg class="sidebar-icon" width="32" height="32" viewBox="0 0 22 22" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M11.2198 17.1838H6.50827C5.45984 17.1838 4.67748 16.2596 5.21167 15.3575C5.98645 14.0491 7.65479 12.5171 11.2198 12.5171M13.442 15.8505L14.5531 17.1838L17.442 13.1838M13.8865 7.18381C13.8865 8.7793 12.5931 10.0727 10.9976 10.0727C9.40209 10.0727 8.10869 8.7793 8.10869 7.18381C8.10869 5.58832 9.40209 4.29492 10.9976 4.29492C12.5931 4.29492 13.8865 5.58832 13.8865 7.18381Z"
                        stroke="#565A5D" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>إدارة الأدوار والصلحيات</span>
            </a>
        </li>
        <li>
            <a href="{{route('daily_report.index')}}" class="link-sidebar {{ activeRoute('daily_report.index') }}">
                <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 22 22"
                     fill="none">
                    <path
                        d="M13.8889 17.1842L11 15.4064L8.11111 17.1842M6.33334 14.5175H15.6667C16.6485 14.5175 17.4444 13.7216 17.4444 12.7397V6.07307C17.4444 5.09123 16.6485 4.29529 15.6667 4.29529H6.33333C5.3515 4.29529 4.55556 5.09123 4.55556 6.07307V12.7397C4.55556 13.7216 5.3515 14.5175 6.33334 14.5175Z"
                        stroke="#565A5D" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>إدارة التقارير اليومية</span>
            </a>
        </li>
        <li>
            <a href="{{route('activity_logs.index')}}" class="link-sidebar" {{ activeRoute('activity_logs.index') }} >
                <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 22 22"
                     fill="none">
                    <path
                        d="M16.3332 8.29529H12.5554V4.51751M8.99989 13.6286H12.9999M8.99989 10.962H12.9999M14.7777 17.1842H7.22211C6.24027 17.1842 5.44434 16.3882 5.44434 15.4064V6.07307C5.44434 5.09123 6.24027 4.29529 7.22211 4.29529H12.7777L16.5554 8.07307V15.4064C16.5554 16.3882 15.7595 17.1842 14.7777 17.1842Z"
                        stroke="#565A5D" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>سجل عمليات النظام</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.logout') }}" class="link-sidebar logout-button" style="  padding: 0px 18px 0px 0;">
                <svg fill="#000000" height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Exit_1_"> <path d="M52.4501991,28.7678509l-5-4.9990005c-0.3768997-0.3770008-0.9902-0.3770008-1.3671989,0 c-0.3778992,0.3778992-0.3778992,0.9902,0,1.3671989l3.3171997,3.3164005H35.2666016v2h14.1320992l-3.3157005,3.3163986 c-0.3778992,0.377903-0.3778992,0.9902,0,1.3672028c0.1884995,0.1884995,0.4365997,0.2831993,0.6835976,0.2831993 c0.2471008,0,0.4951019-0.0946999,0.6836014-0.2831993l5-5.0010014c0.1817017-0.1816006,0.2831993-0.4277,0.2831993-0.6835995 C52.7333984,29.1946507,52.6319008,28.9495506,52.4501991,28.7678509z"></path> <path d="M40.2666016,39.4524498c-0.5527,0-1,0.4473-1,1v10.7900009c0,1.0429993-0.8310013,2.2099991-1.9433022,2.2099991 h-6.0566998V11.2394505V9.8677502L30.0191994,9.33395L14.0765009,2.56445l-0.2606955-0.112h23.507494 c1.2168007,0,1.9433022,0.9921999,1.9433022,1.9511998v15.0487995c0,0.5527,0.4473,1,1,1c0.5527992,0,1-0.4473,1-1V4.4036498 c0-2.1786997-1.7685013-3.9511998-3.9433022-3.9511998H12.2666006c-0.5215998,0-0.9358997,0.4029-0.9822998,0.9124 L11.2666006,1.35725V1.45245V55.03405l17.1855011,7.3064003l2.8144989,1.2070999v-3.0951004v-5h6.0566998 c2.3584023,0,3.9433022-2.1767998,3.9433022-4.2099991V40.4524498 C41.2666016,39.8997498,40.8194008,39.4524498,40.2666016,39.4524498z M29.2665997,11.2394505v49.2129974l-15.999999-6.7766991 V4.4524498l15.9906988,6.7728004l0.0093002,0.0038996V11.2394505z"></path> </g> </g></svg>


                <span class="logout-text">تسجيل الخروج</span>

            </a>
        </li>

    </ul>
    <!-- END SIDEBAR MENU -->
</div>
<!-- END SIDEBAR -->
