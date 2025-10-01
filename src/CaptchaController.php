<?php

namespace Dev3bdulrahman\LaravelCaptcha;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;

class CaptchaController extends Controller
{
    /**
     * Generate captcha data.
     *
     * @param Request $request
     * @param string|null $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(Request $request, ?string $type = null)
    {
        $type = $type ?? $request->get('type', config('captcha.default'));
        $difficulty = $request->get('difficulty', config('captcha.difficulty'));

        try {
            $data = App::make('captcha')->generate($type, $difficulty);
            
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Generate captcha image.
     *
     * @param Request $request
     * @param string|null $type
     * @return \Illuminate\Http\Response
     */
    public function image(Request $request, ?string $type = null)
    {
        $type = $type ?? $request->get('type', 'image');
        $difficulty = $request->get('difficulty', config('captcha.difficulty'));

        try {
            return App::make('captcha')->image($type, $difficulty);
        } catch (\Exception $e) {
            abort(400, $e->getMessage());
        }
    }

    /**
     * Verify captcha input.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $request->validate([
            'captcha' => 'required|string',
            'type' => 'nullable|string',
        ]);

        $type = $request->get('type', config('captcha.default'));
        $input = $request->get('captcha');

        $isValid = App::make('captcha')->verify($input, $type);

        return response()->json([
            'success' => $isValid,
            'message' => $isValid ? 'Captcha verified successfully.' : 'Captcha verification failed.',
        ]);
    }

    /**
     * Refresh captcha.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $type = $request->get('type', config('captcha.default'));
        $difficulty = $request->get('difficulty', config('captcha.difficulty'));

        App::make('captcha')->refresh($type);
        $data = App::make('captcha')->generate($type, $difficulty);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}

