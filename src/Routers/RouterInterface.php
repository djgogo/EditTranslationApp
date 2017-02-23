<?php

namespace Translation\Routers
{
    use Translation\Http\Request;

    interface RouterInterface
    {
        public function route(Request $request);
    }
}
