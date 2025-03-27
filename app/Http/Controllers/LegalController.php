<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DmcaNotification;
use Illuminate\Support\Facades\Log;

class LegalController extends Controller
{
    /**
     * Показать страницу с отказом от ответственности
     */
    public function disclaimer()
    {
        return view('legal.disclaimer');
    }
    
    /**
     * Показать страницу с пользовательским соглашением
     */
    public function terms()
    {
        return view('legal.terms');
    }
    
    /**
     * Показать страницу с формой DMCA (нарушения авторских прав)
     */
    public function dmca()
    {
        return view('legal.dmca');
    }
    
    /**
     * Обработать отправку формы DMCA
     */
    public function dmcaSubmit(Request $request)
    {
        // Валидация данных формы
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content_url' => 'required|url|max:255',
            'original_url' => 'nullable|url|max:255',
            'description' => 'required|string',
            'confirmation' => 'required|accepted',
        ]);
        
        try {
            // Отправка уведомления администрации
            Mail::to(config('app.admin_email', config('app.contact_email', 'admin@example.com')))
                ->send(new DmcaNotification($validated));
                
            // Логирование запроса
            Log::info('DMCA request submitted', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'content_url' => $validated['content_url']
            ]);
                
            return back()->with('success', 'Ваша жалоба успешно отправлена. Мы рассмотрим ее в кратчайшие сроки и свяжемся с вами по указанному email.');
        } catch (\Exception $e) {
            Log::error('Failed to process DMCA request', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);
            
            return back()->withErrors([
                'general' => 'Произошла ошибка при отправке формы. Пожалуйста, попробуйте еще раз или свяжитесь с нами по email.',
            ])->withInput();
        }
    }
}
