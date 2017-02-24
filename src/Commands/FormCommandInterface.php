<?php

namespace Translation\Commands
{

    use Translation\Http\Request;

    interface FormCommandInterface
    {
        public function execute(Request $request);
    }
}
