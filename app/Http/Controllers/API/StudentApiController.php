<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\EmploiTemps;
use App\Models\Jour;
use App\Models\Horaire;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class StudentApiController extends Controller
{
    /**
     * Student login via API
     */
    public function login(Request $request)
    {
        $request->validate([
            'nni' => 'required|string',
            'password' => 'required|string'
        ]);

        try {
            // Find student by NNI
            $student = Student::where('nni', $request->nni)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'الطالب غير موجود'
                ], 404);
            }

            // Check user credentials
            $user = $student->user;
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات الدخول غير صحيحة'
                ], 401);
            }

            // Create token for API authentication
            $token = $user->createToken('student-api-token')->plainTextToken;

            // Prepare class data (nullable)
            $classData = null;
            if ($student->classe) {
                $classData = [
                    'id' => $student->classe->id,
                    'nom' => $student->classe->nom,
                    'niveau' => $student->classe->niveau?->nom,
                    'specialite' => $student->classe->specialite?->nom,
                    'annee' => $student->classe->annee?->annee
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
                'data' => [
                    'student' => [
                        'id' => $student->id,
                        'nni' => $student->nni,
                        'fullname' => $student->fullname,
                        'parent_name' => $student->parent_name,
                        'phone' => $student->phone,
                        'image' => $student->image ? asset('storage/' . $student->image) : null,
                        'class' => $classData,
                        'has_class' => $student->classe !== null
                    ],
                    'token' => $token
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تسجيل الدخول: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get student profile
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();
            $student = Student::where('user_id', $user->id)->with(['classe.niveau', 'classe.specialite', 'classe.annee'])->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'الطالب غير موجود'
                ], 404);
            }

            // Prepare class data (nullable)
            $classData = null;
            if ($student->classe) {
                $classData = [
                    'id' => $student->classe->id,
                    'nom' => $student->classe->nom,
                    'niveau' => $student->classe->niveau?->nom,
                    'specialite' => $student->classe->specialite?->nom,
                    'annee' => $student->classe->annee?->annee
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $student->id,
                    'nni' => $student->nni,
                    'fullname' => $student->fullname,
                    'parent_name' => $student->parent_name,
                    'phone' => $student->phone,
                    'image' => $student->image ? asset('storage/' . $student->image) : null,
                    'class' => $classData,
                    'has_class' => $student->classe !== null
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get student schedule
     */
    public function schedule(Request $request)
    {
        try {
            $user = $request->user();
            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'الطالب غير موجود'
                ], 404);
            }

            // Check if student has a class assigned
            if (!$student->class_id || !$student->classe) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم تعيين قسم لهذا الطالب بعد',
                    'has_class' => false
                ], 400);
            }

            // Get schedule for student's class
            $schedule = EmploiTemps::where('class_id', $student->class_id)
                ->with([
                    'subject',
                    'teacher',
                    'trimester',
                    'annee',
                    'jour',
                    'ref_horaires'
                ])
                ->get()
                ->groupBy('jour.libelle_fr');

            $formattedSchedule = [];
            foreach ($schedule as $day => $emplois) {
                $daySchedule = [];
                foreach ($emplois as $emploi) {
                    $horaires = $emploi->ref_horaires->map(function($horaire) {
                        return [
                            'id' => $horaire->id,
                            'start_time' => $horaire->start_time,
                            'end_time' => $horaire->end_time,
                            'libelle_fr' => $horaire->libelle_fr,
                            'libelle_ar' => $horaire->libelle_ar
                        ];
                    });

                    $daySchedule[] = [
                        'id' => $emploi->id,
                        'subject' => [
                            'id' => $emploi->subject->id,
                            'name' => $emploi->subject->name
                        ],
                        'teacher' => [
                            'id' => $emploi->teacher->id,
                            'name' => $emploi->teacher->name
                        ],
                        'trimester' => [
                            'id' => $emploi->trimester->id,
                            'name' => $emploi->trimester->name
                        ],
                        'horaires' => $horaires,
                        'created_at' => $emploi->created_at,
                        'updated_at' => $emploi->updated_at
                    ];
                }
                $formattedSchedule[$day] = $daySchedule;
            }

            return response()->json([
                'success' => true,
                'message' => 'تم جلب الجدول الزمني بنجاح',
                'data' => [
                    'class_info' => [
                        'id' => $student->classe->id,
                        'nom' => $student->classe->nom
                    ],
                    'schedule' => $formattedSchedule
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الجدول الزمني: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout student
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الخروج بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تسجيل الخروج: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get student schedule data formatted for calendar display
     */
    public function scheduleData(Request $request)
    {
        try {
            $user = $request->user();
            $student = Student::where('user_id', $user->id)->with(['classe.niveau', 'classe.specialite', 'classe.annee'])->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'الطالب غير موجود'
                ], 404);
            }

            // Check if student has a class assigned
            if (!$student->class_id || !$student->classe) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم تعيين قسم لهذا الطالب بعد',
                    'has_class' => false
                ], 400);
            }

            $classe = $student->classe;

            // Get all schedule entries for this class
            $emplois_temps = EmploiTemps::where('class_id', $classe->id)
                ->with(['subject', 'teacher', 'annee', 'jour', 'ref_horaires'])
                ->get();

            // Get time slots and days
            $horaires = Horaire::orderBy('ordre')->get();
            $jours = Jour::orderBy('ordre')->get();

            // Build schedule matrix directly
            $scheduleMatrix = [];

            foreach ($horaires as $horaire) {
                $timeSlot = [
                    'time_info' => [
                        'id' => $horaire->id,
                        'libelle_ar' => $horaire->libelle_ar ?? $horaire->libelle_fr,
                        'libelle_fr' => $horaire->libelle_fr,
                        'heure_debut' => $horaire->heure_debut ?? $horaire->start_time,
                        'heure_fin' => $horaire->heure_fin ?? $horaire->end_time,
                    ],
                    'classes' => []
                ];

                foreach ($jours as $jour) {
                    // Find emploi that matches this day and has this horaire
                    $emploi = $emplois_temps->first(function($e) use ($jour, $horaire) {
                        return $e->jour_id == $jour->id &&
                               $e->ref_horaires->contains('id', $horaire->id);
                    });

                    $classData = [
                        'day_info' => [
                            'id' => $jour->id,
                            'libelle_ar' => $jour->libelle_ar ?? $jour->libelle_fr,
                            'libelle_fr' => $jour->libelle_fr,
                        ],
                        'class_data' => [
                            'has_class' => $emploi ? true : false,
                            'subject' => $emploi ? ($emploi->subject->name ?? '') : '',
                            'teacher' => $emploi ? ($emploi->teacher->nom ?? '') : '',
                        ]
                    ];

                    $timeSlot['classes'][] = $classData;
                }

                $scheduleMatrix[] = $timeSlot;
            }            return response()->json([
                'success' => true,
                'data' => [
                    'student' => [
                        'fullname' => $student->fullname,
                        'nni' => $student->nni,
                        'image' => $student->image ? Storage::url('students/' . $student->image) : null,
                    ],
                    'class_info' => [
                        'nom' => $classe->nom,
                        'niveau' => $classe->niveau?->nom ?? '',
                        'specialite' => $classe->specialite?->nom ?? '',
                    ],
                    'horaires' => $horaires->map(function($h) {
                        return [
                            'id' => $h->id,
                            'libelle_ar' => $h->libelle_ar ?? $h->libelle_fr,
                            'libelle_fr' => $h->libelle_fr,
                            'heure_debut' => $h->heure_debut,
                            'heure_fin' => $h->heure_fin,
                        ];
                    }),
                    'jours' => $jours->map(function($j) {
                        return [
                            'id' => $j->id,
                            'libelle_ar' => $j->libelle_ar ?? $j->libelle_fr,
                            'libelle_fr' => $j->libelle_fr,
                        ];
                    }),
                    'schedule_matrix' => $scheduleMatrix
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات الجدول الزمني: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate student schedule PDF
     */
    public function schedulePdf(Request $request)
    {
        try {
            $user = $request->user();
            $student = Student::where('user_id', $user->id)->with(['classe.niveau', 'classe.specialite', 'classe.annee'])->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'الطالب غير موجود'
                ], 404);
            }

            // Check if student has a class assigned
            if (!$student->class_id || !$student->classe) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم تعيين قسم لهذا الطالب بعد',
                    'has_class' => false
                ], 400);
            }

            // Get schedule data using similar logic to EmploiTempsController
            $classe = $student->classe;
            $emplois_temps = EmploiTemps::where('class_id', $classe->id)->get();

            // Generate calendar data
            $calendarData = $this->generateDataCalendar(Jour::orderBy('ordre')->get(), $emplois_temps);

            // Create PDF
            ob_start();
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'L', // Landscape for better schedule view
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
            ]);

            $mpdf->SetAuthor('Student Schedule');
            $mpdf->SetTitle('جدول الطالب - ' . $student->fullname);
            $mpdf->SetSubject('Student Schedule PDF');
            $mpdf->SetFont('arial', '', 10);

            $mpdf->writeHTML(view('admin.students.pdf.student_schedule_pdf', [
                'student' => $student,
                'classe' => $classe,
                'calendarData' => $calendarData,
                'sctHoraires' => Horaire::get()->sortBy('ordre'),
                'uniqueJours' => Jour::orderBy('ordre')->get(),
                'date_ref' => '',
            ])->render());

            $mpdf->SetHTMLFooter('
                <table width="100%">
                    <tr>
                        <td align="left">الطالب: ' . $student->fullname . '</td>
                        <td align="center">تم الطباعة في: {DATE j-m-Y H:i:s}</td>
                        <td align="right">الصفحة: {PAGENO}/{nbpg}</td>
                    </tr>
                </table>'
            );

            $filename = 'student_schedule_' . $student->nni . '_' . date('Y-m-d') . '.pdf';

            $mpdf->Output($filename, 'D'); // 'D' for download
            ob_end_flush();

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء ملف PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save Expo Push Token for notifications
     */
    public function savePushToken(Request $request)
    {
        try {
            $request->validate([
                'expo_push_token' => 'required|string'
            ]);

            $user = $request->user();
            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'الطالب غير موجود'
                ], 404);
            }

            // Save the push token
            $student->update([
                'expo_push_token' => $request->expo_push_token,
                'notifications_enabled' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ رمز الإشعارات بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ رمز الإشعارات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update notification settings
     */
    public function updateNotificationSettings(Request $request)
    {
        try {
            $request->validate([
                'notifications_enabled' => 'required|boolean'
            ]);

            $user = $request->user();
            $student = Student::where('user_id', $user->id)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'الطالب غير موجود'
                ], 404);
            }

            $student->update([
                'notifications_enabled' => $request->notifications_enabled
            ]);

            return response()->json([
                'success' => true,
                'message' => $request->notifications_enabled
                    ? 'تم تفعيل الإشعارات بنجاح'
                    : 'تم إيقاف الإشعارات بنجاح',
                'data' => [
                    'notifications_enabled' => $student->notifications_enabled
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث إعدادات الإشعارات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate calendar data for schedule display (used by PDF generation)
     */
    private function generateDataCalendar($jours, $emplois_temps)
    {
        $calendarData = [];
        $horaires = Horaire::orderBy('ordre')->get();

        foreach ($horaires as $horaire) {
            $timeSlot = $horaire->libelle_ar ?? $horaire->libelle_fr;
            $calendarData[$timeSlot] = [];

            foreach ($jours as $jour) {
                $emploi = $emplois_temps->where('jour_id', $jour->id)
                                       ->where('horaire_id', $horaire->id)
                                       ->first();

                if ($emploi) {
                    // Get the number of consecutive hours for this subject
                    $nbr_heure = 1;
                    $nextHoraire = $horaires->where('ordre', '>', $horaire->ordre)->first();

                    while ($nextHoraire &&
                           $emplois_temps->where('jour_id', $jour->id)
                                        ->where('horaire_id', $nextHoraire->id)
                                        ->where('subject_id', $emploi->subject_id)
                                        ->where('teacher_id', $emploi->teacher_id)
                                        ->exists()) {
                        $nbr_heure++;
                        $nextHoraire = $horaires->where('ordre', '>', $nextHoraire->ordre)->first();
                    }

                    $calendarData[$timeSlot][] = [
                        'id' => $emploi->id,
                        'matiere' => $emploi->matiere->designation ?? 'غير محدد',
                        'teacher' => $emploi->enseignant->fullname ?? 'غير محدد',
                        'emploi' => $emploi,
                        'nbr_heure' => $nbr_heure,
                        'rowspan' => 1
                    ];
                } else {
                    $calendarData[$timeSlot][] = '';
                }
            }
        }

        return $calendarData;
    }
}
