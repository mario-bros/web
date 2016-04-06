<?php

return array(
    'id' => 'audio_meta',
    'types' => array('post'),
    'title' => __('Audio Post Format Fields', 'charity'),
    'priority' => 'high',
    'template' => array(
                array(
                    'type' => 'upload',
                    'name' => 'audio_ogg',
                    'label' => __('Upload  *.ogg file', 'charity'),
                    'description' => __('Upload  *.ogg file for audio post format', 'charity'),
                ),
                 array(
                    'type' => 'upload',
                    'name' => 'audio_mp3',
                    'label' => __('Upload  *.mp3 file', 'charity'),
                    'description' => __('Upload  *.ogg file for audio post format ', 'charity'),
                ),
                 array(
                    'type' => 'upload',
                    'name' => 'audio_wav',
                    'label' => __('Upload  *.wav file', 'charity'),
                    'description' => __('Upload  *.wav file for audio post format', 'charity'),
                ),
    ),
);

