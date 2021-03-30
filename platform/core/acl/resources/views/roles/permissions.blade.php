<ul id='auto-checkboxes' data-name='foo' class="list-unstyled list-feature">
    <li id="mainNode">
        <input type="checkbox" class="hrv-checkbox" id="expandCollapseAllTree">&nbsp;&nbsp;
        <label for="expandCollapseAllTree" class="label label-default allTree">{{ trans('core/acl::permissions.all') }}</label>
        <ul>
            @foreach($children['root'] as $element_key => $element)
                <li class="collapsed" id="node{{ $element_key }}">
                    <input type="checkbox" class="hrv-checkbox" id="checkSelect{{ $element_key }}" name="flags[]" value="{{ $flags[$element]['flag'] }}" @if (in_array($flags[$element]['flag'], $active)) checked @endif>
                    <label for="checkSelect{{ $element_key }}" class="label label-warning" style="margin: 5px;">{{ $flags[$element]['name'] }}</label>
                    @if (isset($children[$element]))
                        <ul>
                            @foreach($children[$element] as $sub_key => $subElements)
                                <li class="collapsed" id="node_sub_{{ $element_key  }}_{{ $sub_key }}">
                                    <input type="checkbox" class="hrv-checkbox" id="checkSelect_sub_{{ $element_key  }}_{{ $sub_key }}" name="flags[]" value="{{ $flags[$subElements]['flag'] }}" @if (in_array($flags[$subElements]['flag'], $active)) checked @endif>
                                    <label for="checkSelect_sub_{{ $element_key  }}_{{ $sub_key }}" class="label label-primary nameMargin">{{ $flags[$subElements]['name'] }}</label>
                                    @if (isset($children[$subElements]))
                                        <ul>
                                            @foreach($children[$subElements] as $sub_sub_key => $subSubElements)
                                                <li class="collapsed" id="node_sub_sub_{{ $sub_sub_key }}">
                                                    <input type="checkbox" class="hrv-checkbox" id="checkSelect_sub_sub{{ $sub_sub_key }}" name="flags[]" value="{{ $flags[$subSubElements]['flag'] }}" @if (in_array($flags[$subSubElements]['flag'], $active)) checked @endif>
                                                    <label for="checkSelect_sub_sub{{ $sub_sub_key }}" class="label label-success nameMargin">{{ $flags[$subSubElements]['name'] }}</label>
                                                    @if(isset($children[$subSubElements]))
                                                        <ul>
                                                            @foreach($children[$subSubElements] as $grand_children_key => $grandChildrenElements)
                                                                <li class="collapsed" id="node_grand_child{{ $grand_children_key }}">
                                                                    <input type="checkbox" class="hrv-checkbox" id="checkSelect_grand_child{{ $grand_children_key }}" name="flags[]" value="{{ $flags[$grandChildrenElements]['flag'] }}" @if (in_array($flags[$grandChildrenElements]['flag'], $active)) checked @endif>
                                                                    <label for="checkSelect_grand_child{{ $grand_children_key }}" class="label label-danger nameMargin">{{ $flags[$grandChildrenElements]['name'] }}</label>
                                                                    @if(isset($children[$grandChildrenElements]))
                                                                        <ul>
                                                                            @foreach($children[$grandChildrenElements] as $grand_children_key_sub => $greatGrandChildrenElements)
                                                                                <li class="collapsed" id="node{{ $grand_children_key }}">
                                                                                    <input type="checkbox" class="hrv-checkbox" id="checkSelect_grand_child{{ $grand_children_key_sub }}" name="flags[]" value="{{ $flags[$grandChildrenElements]['flag'] }}" @if (in_array($flags[$grandChildrenElements]['flag'], $active)) checked @endif>
                                                                                    <label for="checkSelect_grand_child{{ $grand_children_key_sub }}" class="label label-info nameMargin">{{ $flags[$grandChildrenElements]['name'] }}</label>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </li>
</ul>
