// import 'package:flutter/material.dart';
// import 'package:elevenlabs_agents/elevenlabs_agents.dart';
// import 'package:jazz_smart_pay/app/utils/image_path.dart';
// import 'package:jazz_smart_pay/app/utils/prefrence.dart';
// import 'package:permission_handler/permission_handler.dart';
// import 'dart:math' as math;
// import 'dart:ui';
//
// import '../exports.dart';
// import 'dio_api/dio_api.dart';
//
// class VoiceCallScreen extends StatefulWidget {
//   const VoiceCallScreen({super.key});
//
//   @override
//   State<VoiceCallScreen> createState() => _VoiceCallScreenState();
// }
//
// class _VoiceCallScreenState extends State<VoiceCallScreen>
//     with TickerProviderStateMixin {
//   late ConversationClient _client;
//   late AnimationController _pulseController;
//   late AnimationController _particleController;
//   late AnimationController _bgController;
//   final List<ConversationMessage> _messages = [];
//   String _statusText = 'Ready to connect';
//   String _currentTranscript = '';
//   bool _isConnecting = false;
//
//   String sessionID = '';
//
//   @override
//   void initState() {
//     super.initState();
//     _pulseController = AnimationController(
//       duration: const Duration(seconds: 1),
//       vsync: this,
//     )..repeat(reverse: true);
//
//     _particleController = AnimationController(
//       duration: const Duration(seconds: 4),
//       vsync: this,
//     )..repeat();
//
//     _bgController = AnimationController(
//       duration: const Duration(seconds: 20),
//       vsync: this,
//     )..repeat();
//
//     _requestMicrophonePermission();
//     _initializeClient();
//   }
//
//   Future<void> _requestMicrophonePermission() async {
//     final status = await Permission.microphone.request();
//     if (!status.isGranted && mounted) {
//       ScaffoldMessenger.of(context).showSnackBar(
//         const SnackBar(content: Text('Microphone permission is required')),
//       );
//     }
//   }
//
//   void _initializeClient() {
//     _client = ConversationClient(
//       callbacks: ConversationCallbacks(
//         onConnect: ({required conversationId}) {
//           if (mounted) {
//             setState(() {
//               _statusText = 'Connected ✨';
//               _isConnecting = false;
//             });
//           }
//         },
//         onDisconnect: (details) {
//           if (mounted) {
//             setState(() {
//               _statusText = 'Disconnected';
//               _isConnecting = false;
//             });
//           }
//         },
//         onStatusChange: ({required status}) {
//           if (mounted) {
//             setState(() {
//               _isConnecting = status == ConversationStatus.connecting;
//               _statusText = status == ConversationStatus.connected
//                   ? 'Connected ✨'
//                   : status.name;
//             });
//           }
//         },
//         onMessage: ({required message, required source}) {
//           if (mounted) {
//             setState(() {
//               _messages.add(ConversationMessage(
//                 text: message,
//                 isUser: source == Role.user,
//                 timestamp: DateTime.now(),
//               ));
//             });
//           }
//         },
//         onTentativeUserTranscript: ({required transcript, required eventId}) {
//           if (mounted) setState(() => _currentTranscript = transcript);
//         },
//         onUserTranscript: ({required transcript, required eventId}) {
//           if (mounted) setState(() => _currentTranscript = '');
//         },
//         onError: (message, [context]) {
//           if (mounted) {
//             ScaffoldMessenger.of(context).showSnackBar(
//               SnackBar(content: Text('Error: $message')),
//             );
//             setState(() => _isConnecting = false);
//           }
//         },
//       ),
//     );
//
//     _client.addListener(() => mounted ? setState(() {}) : null);
//   }
//
//   Future<void> _startConversation() async {
//     final token = Prefs().getToken() ?? "";
//     if (token.isEmpty) {
//       ScaffoldMessenger.of(context).showSnackBar(
//         const SnackBar(content: Text('No auth token found')),
//       );
//       return;
//     }
//
//     setState(() => _isConnecting = true);
//
//     try {
//       final userId = 'user_${DateTime.now().millisecondsSinceEpoch}_$token';
//       await _client.startSession(
//         agentId:
//             'agent_7101kf0vfz4nfrtr29wxasnv7ke2', // Replace with your agent ID
//         userId: userId,
//         overrides: ConversationOverrides(
//           agent: AgentOverrides(
//             firstMessage: 'Hello! How can I help you today?',
//             language: 'en',
//           ),
//         ),
//         dynamicVariables: {
//           'AUTH_TOKEN': 'Bearer $token',
//         },
//       );
//     } catch (e) {
//       debugPrint('Start error: $e');
//       if (mounted) {
//         setState(() => _isConnecting = false);
//         ScaffoldMessenger.of(context).showSnackBar(
//           SnackBar(content: Text('Failed to connect: $e')),
//         );
//       }
//     }
//   }
//
//   Future<void> _endConversation() async {
//     await _client.endSession();
//     setState(() => _messages.clear());
//   }
//
//   @override
//   void dispose() {
//     _pulseController.dispose();
//     _particleController.dispose();
//     _bgController.dispose();
//     _client.dispose();
//     super.dispose();
//   }
//
//   @override
//   Widget build(BuildContext context) {
//     final isConnected = _client.status == ConversationStatus.connected;
//     final screenWidth = MediaQuery.of(context).size.width;
//     final screenHeight = MediaQuery.of(context).size.height;
//
//     return Scaffold(
//       body: Stack(
//         children: [
//           // Animated Background
//           AnimatedBuilder(
//             animation: _bgController,
//             builder: (context, child) {
//               return Container(
//                 width: screenWidth,
//                 height: screenHeight,
//                 decoration: BoxDecoration(
//                   gradient: RadialGradient(
//                     colors: [
//                       const Color(0xFF0C0C1E),
//                       const Color(0xFF1A0033),
//                       const Color(0xFF0F0F23),
//                       const Color(0xFF1A1A3A),
//                     ],
//                     stops: const [0.0, 0.3, 0.7, 1.0],
//                     center: Alignment(
//                       _bgController.value * 0.5 - 0.25,
//                       _bgController.value * 0.3 - 0.15,
//                     ),
//                   ),
//                 ),
//               );
//             },
//           ),
//
//           // Floating Particles
//           AnimatedBuilder(
//             animation: _particleController,
//             builder: (context, child) {
//               return CustomPaint(
//                 size: Size.infinite,
//                 painter: ParticlePainter(_particleController.value),
//               );
//             },
//           ),
//
//           SafeArea(
//             child: Column(
//               children: [
//                 // Header with Glass Effect
//                 Container(
//                   margin: const EdgeInsets.all(24),
//                   height: 120,
//                   decoration: BoxDecoration(
//                     borderRadius: BorderRadius.circular(32),
//                     border: Border.all(
//                       color: Colors.white.withOpacity(0.1),
//                       width: 1,
//                     ),
//                     boxShadow: [
//                       BoxShadow(
//                         color: Colors.black.withOpacity(0.3),
//                         blurRadius: 30,
//                         spreadRadius: 0,
//                       ),
//                     ],
//                   ),
//                   child: ClipRRect(
//                     borderRadius: BorderRadius.circular(28),
//                     child: BackdropFilter(
//                       filter: ImageFilter.blur(sigmaX: 20, sigmaY: 20),
//                       child: Container(
//                         padding: const EdgeInsets.all(24),
//                         decoration: BoxDecoration(
//                           gradient: LinearGradient(
//                             colors: [
//                               Colors.white.withOpacity(0.05),
//                               Colors.white.withOpacity(0.02),
//                             ],
//                           ),
//                         ),
//                         child: Row(
//                           children: [
//                             InkWell(
//                                 onTap: () {
//                                   if (_client.status ==
//                                       ConversationStatus.connected) {
//                                     _endConversation();
//                                     Get.back();
//                                   } else {
//                                     Get.back();
//                                   }
//                                 },
//                                 child: const Icon(
//                                   Icons.arrow_back,
//                                   size: 24,
//                                   color: Colors.white,
//                                 )),
//                             const SizedBox(width: 12),
//
//                             // Pulsing AI Avatar
//                             AnimatedBuilder(
//                               animation: _pulseController,
//                               builder: (context, child) {
//                                 return Container(
//                                   padding: EdgeInsets.all(12),
//                                   decoration: BoxDecoration(
//                                     gradient: RadialGradient(
//                                       colors: [
//                                         Colors.cyan.withOpacity(0.8),
//                                         Colors.purple.withOpacity(0.6),
//                                         Colors.blue.withOpacity(0.4),
//                                       ],
//                                     ),
//                                     shape: BoxShape.circle,
//                                     boxShadow: [
//                                       BoxShadow(
//                                         color: Colors.cyan.withOpacity(0.4),
//                                         blurRadius:
//                                             20 + _pulseController.value * 20,
//                                         spreadRadius: 0,
//                                       ),
//                                     ],
//                                   ),
//                                   child: ClipRRect(
//                                     borderRadius: BorderRadius.circular(20),
//                                     child: Image.asset(
//                                       ImagePath.jazzPayLogo,
//                                       width: 34,
//                                       height: 34,
//                                     ),
//                                   ),
//                                 );
//                               },
//                             ),
//                             const SizedBox(width: 12),
//                             Expanded(
//                               child: Column(
//                                 crossAxisAlignment: CrossAxisAlignment.start,
//                                 mainAxisAlignment: MainAxisAlignment.center,
//                                 children: [
//                                   Text(
//                                     'JazzPay Ai Assistant',
//                                     style: TextStyle(
//                                       color: Colors.white.withOpacity(0.9),
//                                       fontSize: 18,
//                                       fontWeight: FontWeight.w700,
//                                       letterSpacing: 0.5,
//                                     ),
//                                   ),
//                                   const SizedBox(height: 4),
//                                   AnimatedSwitcher(
//                                     duration: const Duration(milliseconds: 400),
//                                     child: Text(
//                                       _statusText,
//                                       key: ValueKey(_statusText),
//                                       style: TextStyle(
//                                         color: _isConnecting
//                                             ? Colors.amber.withOpacity(0.9)
//                                             : isConnected
//                                                 ? Colors.green.withOpacity(0.9)
//                                                 : Colors.white.withOpacity(0.7),
//                                         fontSize: 14,
//                                         fontWeight: FontWeight.w500,
//                                       ),
//                                     ),
//                                   ),
//                                 ],
//                               ),
//                             ),
//                             if (_client.status == ConversationStatus.connected)
//                               Container(
//                                 padding: const EdgeInsets.symmetric(
//                                   horizontal: 16,
//                                   vertical: 8,
//                                 ),
//                                 decoration: BoxDecoration(
//                                   gradient: LinearGradient(
//                                     colors: [
//                                       _client.isMuted
//                                           ? Colors.red.withOpacity(0.3)
//                                           : Colors.green.withOpacity(0.3),
//                                       Colors.white.withOpacity(0.1),
//                                     ],
//                                   ),
//                                   borderRadius: BorderRadius.circular(20),
//                                 ),
//                                 child: Icon(
//                                   _client.isMuted ? Icons.mic_off : Icons.mic,
//                                   color: _client.isMuted
//                                       ? Colors.red
//                                       : Colors.green,
//                                   size: 20,
//                                 ),
//                               ),
//                           ],
//                         ),
//                       ),
//                     ),
//                   ),
//                 ),
//
//                 // Live Transcript
//                 if (_currentTranscript.isNotEmpty)
//                   Container(
//                     margin: const EdgeInsets.symmetric(horizontal: 24),
//                     padding: const EdgeInsets.all(16),
//                     decoration: BoxDecoration(
//                       gradient: LinearGradient(
//                         colors: [
//                           Colors.cyan.withOpacity(0.2),
//                           Colors.cyan.withOpacity(0.05),
//                         ],
//                       ),
//                       borderRadius: BorderRadius.circular(20),
//                       border: Border.all(
//                         color: Colors.cyan.withOpacity(0.3),
//                       ),
//                     ),
//                     child: Row(
//                       children: [
//                         Icon(Icons.record_voice_over,
//                             color: Colors.cyan.withOpacity(0.9), size: 20),
//                         const SizedBox(width: 12),
//                         Expanded(
//                           child: Text(
//                             _currentTranscript,
//                             style: TextStyle(
//                               color: Colors.white.withOpacity(0.9),
//                               fontSize: 16,
//                             ),
//                           ),
//                         ),
//                       ],
//                     ),
//                   ),
//
//                 // Messages (Minimal)
//                 Expanded(
//                   child: _messages.isEmpty
//                       ? Center(
//                           child: Column(
//                             mainAxisAlignment: MainAxisAlignment.center,
//                             children: [
//                               Container(
//                                 width: 120,
//                                 height: 120,
//                                 decoration: BoxDecoration(
//                                   gradient: LinearGradient(
//                                     colors: [
//                                       Colors.white.withOpacity(0.1),
//                                       Colors.white.withOpacity(0.05),
//                                     ],
//                                   ),
//                                   shape: BoxShape.circle,
//                                 ),
//                                 child: Icon(
//                                   Icons.chat_bubble_outline_rounded,
//                                   color: Colors.white.withOpacity(0.5),
//                                   size: 60,
//                                 ),
//                               ),
//                               const SizedBox(height: 24),
//                               Text(
//                                 'Click on dial icon to start chatting...',
//                                 style: TextStyle(
//                                   color: Colors.white.withOpacity(0.6),
//                                   fontSize: 18,
//                                 ),
//                               ),
//                             ],
//                           ),
//                         )
//                       : Container(
//                           margin: const EdgeInsets.symmetric(horizontal: 24),
//                           child: ListView.builder(
//                             itemCount: _messages.length,
//                             itemBuilder: (context, index) =>
//                                 _buildNeumorphicBubble(
//                               _messages[index],
//                             ),
//                           ),
//                         ),
//                 ),
//
//                 // Control Panel
//                 Container(
//                   margin: const EdgeInsets.all(32),
//                   child: Column(
//                     children: [
//                       // Main Call Button
//                       GestureDetector(
//                         onTap: _isConnecting
//                             ? null
//                             : (_client.status == ConversationStatus.connected
//                                 ? _endConversation
//                                 : _startConversation),
//                         child: AnimatedBuilder(
//                           animation: Listenable.merge(
//                               [_pulseController, _particleController]),
//                           builder: (context, child) {
//                             return Container(
//                               width: 100,
//                               height: 100,
//                               decoration: BoxDecoration(
//                                 gradient: RadialGradient(
//                                   colors: _isConnecting
//                                       ? [
//                                           Colors.amber.withOpacity(0.9),
//                                           Colors.orange.withOpacity(0.7),
//                                         ]
//                                       : _client.status ==
//                                               ConversationStatus.connected
//                                           ? [
//                                               Colors.red.withOpacity(0.9),
//                                               Colors.redAccent.withOpacity(0.7),
//                                             ]
//                                           : [
//                                               Colors.green.withOpacity(0.9),
//                                               Colors.blueAccent
//                                                   .withOpacity(0.7),
//                                             ],
//                                 ),
//                                 shape: BoxShape.circle,
//                                 boxShadow: [
//                                   BoxShadow(
//                                     color: (_isConnecting
//                                             ? Colors.amber
//                                             : _client.status ==
//                                                     ConversationStatus.connected
//                                                 ? Colors.red
//                                                 : Colors.green)
//                                         .withOpacity(0.5),
//                                     blurRadius: 40,
//                                     spreadRadius: 8,
//                                   ),
//                                   BoxShadow(
//                                     color: Colors.black.withOpacity(0.4),
//                                     blurRadius: 20,
//                                     spreadRadius: 0,
//                                   ),
//                                 ],
//                               ),
//                               child: _isConnecting
//                                   ? const SizedBox(
//                                       width: 50,
//                                       height: 50,
//                                       child: CircularProgressIndicator(
//                                         strokeWidth: 4,
//                                         valueColor:
//                                             AlwaysStoppedAnimation<Color>(
//                                           Colors.white,
//                                         ),
//                                       ),
//                                     )
//                                   : Icon(
//                                       _client.status ==
//                                               ConversationStatus.connected
//                                           ? Icons.call_end_rounded
//                                           : Icons.call_rounded,
//                                       color: Colors.white,
//                                       size: 40,
//                                     ),
//                             );
//                           },
//                         ),
//                       ),
//                       const SizedBox(height: 24),
//                       if (_client.canSendFeedback)
//                         Row(
//                           mainAxisAlignment: MainAxisAlignment.center,
//                           children: [
//                             _buildFloatingAction(
//                                 Icons.thumb_up_rounded,
//                                 Colors.green,
//                                 () => _client.sendFeedback(isPositive: true)),
//                             const SizedBox(width: 24),
//                             _buildFloatingAction(
//                                 Icons.thumb_down_rounded,
//                                 Colors.red,
//                                 () => _client.sendFeedback(isPositive: false)),
//                           ],
//                         ),
//                     ],
//                   ),
//                 ),
//               ],
//             ),
//           ),
//         ],
//       ),
//     );
//   }
//
//   Widget _buildNeumorphicBubble(ConversationMessage message) {
//     return Align(
//       alignment: message.isUser ? Alignment.centerRight : Alignment.centerLeft,
//       child: Container(
//         margin: const EdgeInsets.only(bottom: 16),
//         padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
//         decoration: BoxDecoration(
//           gradient: LinearGradient(
//             colors: message.isUser
//                 ? [Colors.cyan.withOpacity(0.25), Colors.cyan.withOpacity(0.1)]
//                 : [
//                     Colors.purple.withOpacity(0.2),
//                     Colors.purple.withOpacity(0.05)
//                   ],
//           ),
//           borderRadius: BorderRadius.circular(24),
//           border: Border.all(
//             color:
//                 (message.isUser ? Colors.cyan : Colors.purple).withOpacity(0.3),
//           ),
//           boxShadow: [
//             BoxShadow(
//               color: (message.isUser ? Colors.cyan : Colors.purple)
//                   .withOpacity(0.3),
//               blurRadius: 12,
//               offset: const Offset(0, 6),
//             ),
//           ],
//         ),
//         constraints: BoxConstraints(
//           maxWidth: MediaQuery.of(context).size.width * 0.7,
//         ),
//         child: Text(
//           message.text,
//           style: TextStyle(
//             color: Colors.white.withOpacity(0.95),
//             fontSize: 15,
//             height: 1.4,
//           ),
//         ),
//       ),
//     );
//   }
//
//   Widget _buildFloatingAction(
//       IconData icon, Color color, VoidCallback onPressed) {
//     return Container(
//       width: 56,
//       height: 56,
//       decoration: BoxDecoration(
//         gradient: LinearGradient(
//           colors: [color.withOpacity(0.3), color.withOpacity(0.1)],
//         ),
//         shape: BoxShape.circle,
//         border: Border.all(color: color.withOpacity(0.5)),
//         boxShadow: [
//           BoxShadow(
//             color: color.withOpacity(0.3),
//             blurRadius: 20,
//             spreadRadius: 2,
//           ),
//         ],
//       ),
//       child: IconButton(
//         onPressed: onPressed,
//         icon: Icon(icon, color: color, size: 24),
//       ),
//     );
//   }
// }
//
// class ParticlePainter extends CustomPainter {
//   final double animation;
//
//   ParticlePainter(this.animation);
//
//   @override
//   void paint(Canvas canvas, Size size) {
//     final paint = Paint()..color = Colors.cyan.withOpacity(0.6);
//
//     for (int i = 0; i < 20; i++) {
//       final progress = animation + i * 0.1;
//       final x = size.width * (0.1 + 0.8 * (progress % 1.0));
//       final y = size.height * (0.2 + 0.6 * math.sin(progress * math.pi * 2));
//       final radius = 2 + 2 * math.sin(progress * math.pi * 4);
//
//       canvas.drawCircle(Offset(x, y), radius, paint);
//     }
//   }
//
//   @override
//   bool shouldRepaint(covariant CustomPainter oldDelegate) => true;
// }
//
// class ConversationMessage {
//   final String text;
//   final bool isUser;
//   final DateTime timestamp;
//
//   ConversationMessage({
//     required this.text,
//     required this.isUser,
//     required this.timestamp,
//   });
// }


import 'package:flutter/material.dart';
import 'package:elevenlabs_agents/elevenlabs_agents.dart';
import 'package:jazz_smart_pay/app/utils/prefrence.dart';
import 'package:permission_handler/permission_handler.dart';

class SimpleVoiceCallScreen extends StatefulWidget {
  const SimpleVoiceCallScreen({super.key});

  @override
  State<SimpleVoiceCallScreen> createState() => _SimpleVoiceCallScreenState();
}

class _SimpleVoiceCallScreenState extends State<SimpleVoiceCallScreen>
    with TickerProviderStateMixin {
  ConversationClient? _client;
  late AnimationController _pulseController;
  String _statusText = 'Tap to call';
  bool _isCalling = false;
  bool _isMuted = false;
  bool _isDisposed = false;

  @override
  void initState() {
    super.initState();
    _pulseController = AnimationController(
      duration: const Duration(seconds: 1),
      vsync: this,
    );
    _initializeClient();
    _requestMicrophonePermission();
  }

  Future<void> _requestMicrophonePermission() async {
    final status = await Permission.microphone.request();
    if (!status.isGranted && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Microphone permission required')),
      );
    }
  }

  void _initializeClient() {
    _client = ConversationClient(
      callbacks: ConversationCallbacks(
        onConnect: ({required conversationId}) {
          if (mounted && !_isDisposed) {
            setState(() {
              _statusText = 'Connected';
              _isCalling = true;
              _isMuted = false;
              _pulseController.repeat(reverse: true);
            });
          }
        },
        onDisconnect: (details) {
          if (mounted && !_isDisposed) {
            _resetCallState();
          }
        },
        onStatusChange: ({required status}) {
          if (mounted && !_isDisposed) {
            setState(() {
              _isCalling = status == ConversationStatus.connecting;
              _statusText = status == ConversationStatus.connected
                  ? 'Connected'
                  : status.name;
            });
          }
        },
        // onError: (message, dynamic error) {
        //   debugPrint('Call error: $message');
        //   if (mounted && !_isDisposed) {
        //     WidgetsBinding.instance.addPostFrameCallback((_) {
        //       if (mounted) {
        //         ScaffoldMessenger.of(context).showSnackBar(
        //           SnackBar(content: Text('Error: $message')),
        //         );
        //         _resetCallState();
        //       }
        //     });
        //   }
        // },
      ),
    );
  }

  void _resetCallState() {
    setState(() {
      _statusText = 'Tap to call';
      _isCalling = false;
      _isMuted = false;
      _pulseController.stop();
      _pulseController.reset();
    });
  }

  Future<void> _toggleCall() async {
    if (_isDisposed || _client == null) return;

    if (_isCalling) {
      // End call - proper WebRTC cleanup
      try {
        await Future.delayed(const Duration(milliseconds: 200));
        await _client!.endSession();
      } catch (e) {
        debugPrint('End call error: $e');
      }
      _resetCallState();
    } else {
      // Start call
      final token = Prefs().getToken() ?? "";
      if (token.isEmpty) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('No auth token')),
        );
        return;
      }

      setState(() {
        _statusText = 'Connecting...';
        _isCalling = true;
      });

      try {
        final userId = 'user_${DateTime.now().millisecondsSinceEpoch}';
        await _client!.startSession(
          agentId: 'agent_1701kfb5p7yvef9830jqqm5tqr3x', // Replace with your agent ID
          userId: userId,

          dynamicVariables: {'AUTH_TOKEN': 'Bearer $token'},
        );
      } catch (e) {
        debugPrint('Start call error: $e');
        _resetCallState();
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Failed to connect: $e')),
        );
      }
    }
  }

  void _toggleMute() {
    setState(() {
      _isMuted = !_isMuted;
    });
    // Note: ElevenLabs handles mute state internally via isMuted property
    // No explicit mute() method needed
  }

  @override
  void dispose() {
    _isDisposed = true;
    _pulseController.stop();
    _client?.endSession();
    _client?.dispose();
    _client = null;
    _pulseController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final screenHeight = MediaQuery.of(context).size.height;

    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(
          gradient: RadialGradient(
            colors: [
              Color(0xFF1A1A2E),
              Color(0xFF16213E),
              Color(0xFF0F0F23),
            ],
            stops: [0.0, 0.5, 1.0],
          ),
        ),
        child: SafeArea(
          child: Column(
            children: [
              // Header
              Container(
                margin: const EdgeInsets.all(24),
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(24),
                  border: Border.all(color: Colors.white.withOpacity(0.2)),
                ),
                child: Row(
                  children: [
                    IconButton(
                      onPressed: () => Navigator.pop(context),
                      icon: const Icon(Icons.arrow_back, color: Colors.white),
                    ),
                    const SizedBox(width: 12),
                    Container(
                      padding: const EdgeInsets.all(12),
                      decoration: const BoxDecoration(
                        gradient: LinearGradient(
                          colors: [Color(0xFF00D4FF), Color(0xFF5A67D8)],
                        ),
                        shape: BoxShape.circle,
                      ),
                      child: const Icon(
                        Icons.smart_toy,
                        color: Colors.white,
                        size: 24,
                      ),
                    ),
                    const SizedBox(width: 16),
                    const Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'JazzPay AI Assistant',
                            style: TextStyle(
                              color: Colors.white,
                              fontSize: 18,
                              fontWeight: FontWeight.w700,
                            ),
                          ),
                          Text(
                            'Voice Assistant',
                            style: TextStyle(
                              color: Colors.white70,
                              fontSize: 14,
                            ),
                          ),
                        ],
                      ),
                    ),
                    if (_isCalling)
                      Container(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 12,
                          vertical: 6,
                        ),
                        decoration: BoxDecoration(
                          color: _isMuted
                              ? Colors.red.withOpacity(0.3)
                              : Colors.green.withOpacity(0.3),
                          borderRadius: BorderRadius.circular(16),
                        ),
                        child: Icon(
                          _isMuted ? Icons.mic_off : Icons.mic,
                          color: _isMuted ? Colors.red : Colors.green,
                          size: 18,
                        ),
                      ),
                  ],
                ),
              ),

              // Status & Timer
              Padding(
                padding: const EdgeInsets.symmetric(vertical: 40),
                child: Column(
                  children: [
                    AnimatedSwitcher(
                      duration: const Duration(milliseconds: 300),
                      child: Text(
                        _statusText,
                        key: ValueKey(_statusText),
                        style: TextStyle(
                          color: _isCalling ? Colors.green : Colors.white,
                          fontSize: 24,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      _isCalling ? '00:00' : 'Tap button to start call',
                      style: TextStyle(
                        color: Colors.white54,
                        fontSize: 16,
                      ),
                    ),
                  ],
                ),
              ),

              // Pulsing Avatar
              Expanded(
                child: AnimatedBuilder(
                  animation: _pulseController,
                  builder: (context, child) {
                    return Center(
                      child: Container(
                        width: 200,
                        height: 200,
                        decoration: BoxDecoration(
                          gradient: LinearGradient(
                            colors: [
                              Colors.cyan.withOpacity(0.3 + _pulseController.value * 0.2),
                              Colors.purple.withOpacity(0.2 + _pulseController.value * 0.1),
                            ],
                          ),
                          shape: BoxShape.circle,
                          boxShadow: [
                            BoxShadow(
                              color: Colors.cyan.withOpacity(0.4 * _pulseController.value),
                              blurRadius: 40,
                              spreadRadius: 10 * _pulseController.value,
                            ),
                          ],
                        ),
                        child: const CircleAvatar(
                          radius: 80,
                          backgroundColor: Colors.white10,
                          child: Icon(
                            Icons.smart_toy,
                            size: 100,
                            color: Colors.white54,
                          ),
                        ),
                      ),
                    );
                  },
                ),
              ),

              // Call Controls
              Container(
                margin: const EdgeInsets.all(32),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    // Mute Button
                    if (_isCalling)
                      _buildControlButton(
                        _isMuted ? Icons.mic_off : Icons.mic,
                        _isMuted ? Colors.red : Colors.white54,
                        _toggleMute,
                      ),

                    const SizedBox(width: 24),

                    // Main Call Button
                    GestureDetector(
                      onTap: _isDisposed ? null : _toggleCall,
                      child: Container(
                        width: 90,
                        height: 90,
                        decoration: BoxDecoration(
                          gradient: LinearGradient(
                            colors: _isCalling
                                ? const [Colors.red, Color(0xFFE53E3E)]
                                : const [Colors.green, Color(0xFF38A169)],
                          ),
                          shape: BoxShape.circle,
                          boxShadow: [
                            BoxShadow(
                              color: (_isCalling ? Colors.red : Colors.green)
                                  .withOpacity(0.5),
                              blurRadius: 30,
                              spreadRadius: 5,
                            ),
                          ],
                        ),
                        child: Icon(
                          _isCalling ? Icons.call_end : Icons.call,
                          color: Colors.white,
                          size: 40,
                        ),
                      ),
                    ),

                    const SizedBox(width: 24),

                    // Speaker Button (placeholder)
                    if (_isCalling)
                      _buildControlButton(
                        Icons.volume_up,
                        Colors.white54,
                            () {}, // No speaker control in ElevenLabs SDK
                      ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildControlButton(IconData icon, Color color, VoidCallback onTap) {
    return GestureDetector(
      onTap: _isDisposed || !_isCalling ? null : onTap,
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white.withOpacity(0.15),
          shape: BoxShape.circle,
        ),
        child: Icon(icon, color: color, size: 28),
      ),
    );
  }
}

